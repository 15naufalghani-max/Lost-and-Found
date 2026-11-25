<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchAndExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_and_filters_on_items_index(): void
    {
        $tumbler = Item::factory()->found()->create([
            'title' => 'Tumbler Corkcicle Biru',
            'category' => 'Lainnya',
            'location' => 'Perpustakaan',
            'status' => Item::STATUS_OPEN,
        ]);

        $dompet = Item::factory()->lost()->create([
            'title' => 'Dompet Kulit Hitam',
            'category' => 'Dompet & Tas',
            'location' => 'Lab Komputer FIK',
            'status' => Item::STATUS_RESOLVED,
        ]);

        $this->get(route('items.index', ['search' => 'Corkcicle']))
            ->assertOk()
            ->assertSee($tumbler->title)
            ->assertDontSee($dompet->title);

        $this->get(route('items.index', ['category' => 'Dompet & Tas', 'status' => 'resolved']))
            ->assertOk()
            ->assertSee($dompet->title)
            ->assertDontSee($tumbler->title);
    }

    public function test_overdue_open_items_transition_to_expired_automatically_and_via_command(): void
    {
        $overdue = Item::factory()->lost()->create([
            'title' => 'Payung Lipat',
            'status' => Item::STATUS_OPEN,
            'expires_at' => now()->subMinute(),
        ]);

        $active = Item::factory()->found()->create([
            'title' => 'Kunci Loker',
            'status' => Item::STATUS_OPEN,
            'expires_at' => now()->addDays(10),
        ]);

        $this->artisan('items:expire')->assertSuccessful();

        $this->assertSame(Item::STATUS_EXPIRED, $overdue->fresh()->status);
        $this->assertSame(Item::STATUS_OPEN, $active->fresh()->status);

        $overdue2 = Item::factory()->lost()->create([
            'title' => 'Jaket Almamater',
            'status' => Item::STATUS_OPEN,
            'expires_at' => now()->subHour(),
        ]);

        $this->get(route('items.index', ['status' => 'expired']))
            ->assertOk()
            ->assertSee('Jaket Almamater');

        $this->assertSame(Item::STATUS_EXPIRED, $overdue2->fresh()->status);
    }

    public function test_lost_item_show_suggests_smart_matches_with_keyword_fallback(): void
    {
        config(['services.gemini.key' => null]);

        $lost = Item::factory()->lost()->create([
            'title' => 'Tumbler Corkcicle Biru',
            'description' => 'Botol minum tumbler biru dengan stiker kucing di Perpustakaan.',
        ]);

        $matchingFound = Item::factory()->found()->create([
            'title' => 'Ditemukan Tumbler Biru',
            'description' => 'Tumbler biru ada stiker kucing di meja lantai 2.',
        ]);

        $unrelatedFound = Item::factory()->found()->create([
            'title' => 'Payung Hitam',
            'description' => 'Payung tertinggal di parkiran motor.',
        ]);

        $this->get(route('items.show', $lost))
            ->assertOk()
            ->assertSee($matchingFound->title)
            ->assertDontSee($unrelatedFound->title);
    }

    public function test_profile_index_displays_user_items_and_claims(): void
    {
        $user = User::factory()->create();
        $myItem = Item::factory()->for($user)->create(['title' => 'Flashdisk Kingston']);
        $otherItem = Item::factory()->found()->create(['title' => 'Kacamata Minus']);
        Claim::factory()->for($otherItem)->for($user)->create();

        $this->actingAs($user)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee($myItem->title)
            ->assertSee($otherItem->title);
    }
}
