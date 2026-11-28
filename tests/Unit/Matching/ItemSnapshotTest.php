<?php

namespace Tests\Unit\Matching;

use App\Models\Item;
use App\Services\Matching\ItemSnapshot;
use Carbon\CarbonInterface;
use Tests\TestCase;

class ItemSnapshotTest extends TestCase
{
    public function test_missing_event_at_falls_back_to_a_date(): void
    {
        $item = (new Item)->forceFill(['id' => 1, 'title' => 'a', 'description' => 'b', 'location' => 'c']);

        $this->assertInstanceOf(CarbonInterface::class, ItemSnapshot::from($item)->eventAt);
    }
}