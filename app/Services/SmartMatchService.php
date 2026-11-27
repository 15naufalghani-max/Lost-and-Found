<?php

namespace App\Services;

use App\Models\Item;
use App\Services\Matching\ItemSnapshot;
use App\Services\Matching\KeywordMatcher;
use App\Services\Matching\MatchProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmartMatchService
{
    private const CACHE_MINUTES = 15;

    public function __construct(
        private readonly MatchProvider $ai,
        private readonly KeywordMatcher $fallback,
    ) {}

    /** @return Collection<int, array{item: Item, score: int, reason: string, source: string}> */
    public function matchesFor(Item $lost): Collection
    {
        if ($lost->type !== Item::TYPE_LOST || $lost->status !== Item::STATUS_OPEN) {
            return collect();
        }

        $candidates = $this->candidates($lost);
        if ($candidates->isEmpty()) {
            return collect();
        }

        $result = $this->scored($lost, $candidates);
        $byId = $candidates->keyBy('id');

        return collect($result['scores'])
            ->filter(fn (array $r) => ($r['score'] ?? 0) > 0 && $byId->has($r['id']))
            ->sortByDesc('score')
            ->take(3)
            ->map(fn (array $r) => [
                'item'   => $byId->get($r['id']),
                'score'  => (int) $r['score'],
                'reason' => (string) ($r['reason'] ?? ''),
                'source' => $result['source'],
            ])
            ->values();
    }

    private function candidates(Item $lost): Collection
    {
        $base = Item::query()->found()->open()->where('id', '!=', $lost->id);
        $sameCategory = (clone $base)->where('category', $lost->category);

        return ($sameCategory->count() >= 3 ? $sameCategory : $base)
            ->latest()->limit(10)->get();
    }

    /** @return array{source: string, scores: array<int, array{id:int, score:int, reason:string}>} */
    private function scored(Item $lost, Collection $candidates): array
    {
        $key = sprintf('smartmatch:%d:%s', $lost->id, md5($lost->updated_at.'|'.$candidates->pluck('id')->implode(',')));

        if ($cached = Cache::get($key)) {
            return $cached;
        }

        $lostSnap = ItemSnapshot::from($lost);
        $snaps = $candidates->map(fn (Item $c) => ItemSnapshot::from($c))->values();

        try {
            $result = ['source' => 'ai', 'scores' => $this->ai->score($lostSnap, $snaps)];
            Cache::put($key, $result, now()->addMinutes(self::CACHE_MINUTES));

            return $result;
        } catch (\Throwable $e) {
            Log::warning('Smart Matching memakai fallback kata kunci: '.$e->getMessage());

            return ['source' => 'keyword', 'scores' => $this->fallback->score($lostSnap, $snaps)];
        }
    }
}