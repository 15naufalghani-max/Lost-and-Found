<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClaimHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_guess_list_no_longer_auto_approves(): void
    {
        $owner = User::factory()->create();
        $claimant = User::factory()->create();
        $item = Item::factory()->for($owner)->found()->create(['verification_answer' => 'kuning']);

        $this->actingAs($claimant)
            ->post(route('claims.store', $item), ['answer' => 'merah biru kuning hijau hitam putih'])
            ->assertRedirect(route('items.show', $item));

        $this->assertSame(Claim::STATUS_PENDING, Claim::first()->status);
        $this->assertSame(Item::STATUS_OPEN, $item->fresh()->status);
    }

    public function test_claimant_is_limited_to_three_attempts_per_item(): void
    {
        $item = Item::factory()->for(User::factory()->create())->found()->create();
        $claimant = User::factory()->create();
        Claim::factory()->count(3)->for($item)->for($claimant)->create(['status' => Claim::STATUS_REJECTED]);

        $this->actingAs($claimant)
            ->post(route('claims.store', $item), ['answer' => 'tebakan keempat'])
            ->assertSessionHas('error');

        $this->assertSame(3, Claim::count());
    }

    public function test_only_claimant_can_cancel_a_pending_claim(): void
    {
        $item = Item::factory()->for(User::factory()->create())->found()->create();
        $claimant = User::factory()->create();
        $claim = Claim::factory()->for($item)->for($claimant)->create(['status' => Claim::STATUS_PENDING]);

        $this->actingAs(User::factory()->create())->delete(route('claims.cancel', $claim))->assertForbidden();
        $this->actingAs($claimant)->delete(route('claims.cancel', $claim))->assertRedirect();

        $this->assertDatabaseMissing('claims', ['id' => $claim->id]);
    }

    public function test_only_claimant_and_owner_can_download_proof(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('claims/bukti.jpg', 'x');

        $owner = User::factory()->create();
        $claimant = User::factory()->create();
        $item = Item::factory()->for($owner)->found()->create();
        $claim = Claim::factory()->for($item)->for($claimant)->create(['proof_image' => 'claims/bukti.jpg']);

        $this->actingAs(User::factory()->create())->get(route('claims.proof', $claim))->assertForbidden();
        $this->actingAs($owner)->get(route('claims.proof', $claim))->assertOk();
    }
}