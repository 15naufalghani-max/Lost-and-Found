<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Services\Matching\ItemSnapshot;
use App\Services\Matching\MatchProvider;
use App\Services\SmartMatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SmartMatchServiceTest extends TestCase
{
    use RefreshDatabase;

    private function items(): array
    {
        $o = User::factory()->create();
        $lost = Item::factory()->for($o)->lost()->create(['title' => 'Tumbler hitam', 'description' => 'Tumbler hitam stiker bunga', 'event_at' => now()]);
        $similar = Item::factory()->for($o)->found()->create(['title' => 'Tumbler gelap', 'description' => 'Tumbler hitam stiker bunga', 'event_at' => now()]);
        Item::factory()->for($o)->found()->create(['title' => 'Payung', 'description' => 'Payung lipat biru', 'event_at' => now()]);

        return [$lost, $similar];
    }

    public function test_falls_back_to_keywords_when_ai_fails(): void
    {
        [$lost, $similar] = $this->items();
        $this->app->instance(MatchProvider::class, new class implements MatchProvider {
            public function score(ItemSnapshot $lost, Collection $candidates): array
            {
                throw new \RuntimeException('API down');
            }
        });

        $result = $this->app->make(SmartMatchService::class)->matchesFor($lost);

        $this->assertSame('keyword', $result->first()['source']);
        $this->assertSame($similar->id, $result->first()['item']->id);
    }

    public function test_ai_result_is_cached(): void
    {
        [$lost, $similar] = $this->items();
        $spy = new class implements MatchProvider {
            public int $calls = 0;
            public int $target = 0;

            public function score(ItemSnapshot $lost, Collection $candidates): array
            {
                $this->calls++;

                return [['id' => $this->target, 'score' => 90, 'reason' => 'mirip']];
            }
        };
        $spy->target = $similar->id;
        $this->app->instance(MatchProvider::class, $spy);

        $service = $this->app->make(SmartMatchService::class);
        $first = $service->matchesFor($lost);
        $service->matchesFor($lost);

        $this->assertSame('ai', $first->first()['source']);
        $this->assertSame(1, $spy->calls);
    }
}