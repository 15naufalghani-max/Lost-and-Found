<?php

namespace App\Services\Matching;

use Carbon\CarbonInterface;

final readonly class ItemSnapshot
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $location,
        public CarbonInterface $eventAt,
    ) {}

    public static function from(\App\Models\Item $item): self
    {
        return new self(
            $item->id,
            (string) $item->title,
            (string) $item->description,
            (string) $item->location,
            $item->event_at,
            $item->event_at ?? $item->created_at ?? now(),
        );
    }
}