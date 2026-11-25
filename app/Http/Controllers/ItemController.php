<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a paginated listing of lost and found items.
     */
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        $items = Item::with('user')
            ->when(in_array($type, Item::TYPES, true), fn ($q) => $q->where('type', $type))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return $this->renderView('items.index', 'backend.items.index', [
            'items' => $items,
            'categories' => Item::CATEGORIES,
            'locations' => Item::LOCATIONS,
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
        $data['expires_at'] = now()->addDays(30);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        return redirect()->route('items.show', $item)
            ->with('status', 'Laporan barang berhasil dipublikasikan.');
    }

    /**
     * Display the specified item along with its comments.
     */
    public function show(Item $item): View
    {
        $item->load([
            'user',
            'comments' => fn ($q) => $q->with('user')->latest(),
        ]);

        return $this->renderView('items.show', 'backend.items.show', [
            'item' => $item,
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
     * Render frontend Blade view when available, otherwise use backend fallback view.
     *
     * @param array<string, mixed> $data
     */
    private function renderView(string $primary, string $fallback, array $data = []): View
    {
        return view()->exists($primary) ? view($primary, $data) : view($fallback, $data);
    }
}
