<?php

namespace App\Services\Matching;

use Illuminate\Support\Collection;

final class KeywordMatcher
{
    public function score(ItemSnapshot $lost, Collection $cands): array
    {
        $a = $this->tokens($lost->title.' '.$lost->description);

        return $cands->map(function (ItemSnapshot $c) use ($a) {
            $b = $this->tokens($c->title.' '.$c->description);
            $union = $a->merge($b)->unique()->count();
            $score = $union ? (int) round($a->intersect($b)->count() / $union * 100) : 0;

            return ['id' => $c->id, 'score' => $score, 'reason' => 'Kecocokan kata kunci'];
        })->values()->all();
    }

    private function tokens(string $s): Collection
    {
        return collect(preg_split('/\W+/u', mb_strtolower($s), -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn ($w) => mb_strlen($w) > 2)
            ->unique()
            ->values();
    }
}