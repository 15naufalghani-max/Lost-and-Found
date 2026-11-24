<?php

namespace App\Services\Matching;

use Illuminate\Support\Collection;

interface MatchProvider
{
    /**
     * @param  Collection<int, ItemSnapshot>  $candidates
     * @return array<int, array{id:int, score:int, reason:string}>
     */
    public function score(ItemSnapshot $lost, Collection $candidates): array;
}