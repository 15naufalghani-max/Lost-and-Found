<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Services\SmartMatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a filtered and paginated listing of lost and found items.
     */
    public function index(Request $request): View
    {
        Item::expireOverdue();

        $filters = $request->only(['search', 'q', 'type', 'category', 'location', 'status']);

        $items = Item::with('user')
            ->filter($filters)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return $this->renderView('items.index', 'backend.items.index', [
            'items' => $items,
            'filters' => $filters,
            'categories' => Item::CATEGORIES,
            'locations' => Item::LOCATIONS,
            'statuses' => Item::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new lost or found item report.
     */
    public function create(): View
    {
        return $this->renderView('items.create', 'backend.items.create', [
            'categories' => Item::CATEGORIES,
            'locations' => Item::LOCATIONS,
            'types' => Item::TYPES,
        ]);
    }

    /**
     * Store a newly created item report in storage.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = Item::STATUS_OPEN;
        $data['event_at'] = $data['event_at'] ?? now();
        $data['expires_at'] = $data['expires_at'] ?? now()->addDays(30);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        return redirect()->route('items.show', $item)
            ->with('status', 'Laporan barang berhasil dipublikasikan.');
    }

    /**
     * Display the specified item along with comments, claims, and smart matches.
     */
    public function show(Item $item, SmartMatchService $smartMatch): View
    {
        if ($item->status === Item::STATUS_OPEN && $item->isExpired()) {
            $item->update(['status' => Item::STATUS_EXPIRED]);
        }

        $item->load([
            'user',
            'comments' => fn ($q) => $q->with('user')->latest(),
            'claims' => fn ($q) => $q->with('user')->latest(),
        ]);

        $matches = $smartMatch->matchesFor($item);

        return $this->renderView('items.show', 'backend.items.show', [
            'item' => $item,
            'matches' => $matches,
        ]);
    }

    /**
     * Show the form for editing an item owned by the current user.
     */
    public function edit(Request $request, Item $item): View
    {
        abort_if($item->user_id !== $request->user()->id, 403);

        return $this->renderView('items.edit', 'backend.items.edit', [
            'item' => $item,
            'categories' => Item::CATEGORIES,
            'locations' => Item::LOCATIONS,
            'types' => Item::TYPES,
        ]);
    }

    /**
     * Update the specified item in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.show', $item)
            ->with('status', 'Laporan barang berhasil diperbarui.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Request $request, Item $item): RedirectResponse
    {
        abort_if($item->user_id !== $request->user()->id, 403);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('status', 'Laporan barang berhasil dihapus.');
    }

    /**
     * Suggest matching found items for an open lost item using AI with keyword fallback.
     *
     * @return Collection<int, array{item: Item, score: int, reason: string}>
     */
    private function findSmartMatches(Item $item, MatchProvider $matcher, KeywordMatcher $fallback): Collection
    {
        if ($item->type !== Item::TYPE_LOST || $item->status !== Item::STATUS_OPEN) {
            return collect();
        }

        $candidates = Item::query()
            ->found()
            ->open()
            ->where('id', '!=', $item->id)
            ->latest()
            ->limit(10)
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        $lostSnapshot = ItemSnapshot::from($item);
        $candSnapshots = $candidates->map(fn (Item $c) => ItemSnapshot::from($c));

        try {
            $scores = $matcher->score($lostSnapshot, $candSnapshots);
        } catch (\Throwable) {
            $scores = $fallback->score($lostSnapshot, $candSnapshots);
        }

        $byId = $candidates->keyBy('id');

        return collect($scores)
            ->filter(fn (array $row) => ($row['score'] ?? 0) > 0 && $byId->has($row['id']))
            ->sortByDesc('score')
            ->take(3)
            ->map(fn (array $row) => [
                'item' => $byId->get($row['id']),
                'score' => (int) $row['score'],
                'reason' => (string) ($row['reason'] ?? ''),
            ])
            ->values();
    }

    /**
     * Render frontend Blade view when available, otherwise use backend fallback view.
     *
     * @param array<string, mixed> $data
     */
    private function renderView(string $primary, string $fallback, array $data = []): View
    {
        return view()->exists($primary) ? view($primary, $data) : view($fallback, $data);
    }
}
