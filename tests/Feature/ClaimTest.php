<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_owner_can_submit_pending_claim_and_owner_can_approve(): void
    {
        $owner = User::factory()->create();
        $claimant = User::factory()->create();
        $otherClaimant = User::factory()->create();

        $item = Item::factory()->for($owner)->found()->create([
            'verification_answer' => 'stiker kucing hitam',
        ]);

        $this->actingAs($claimant)->get(route('claims.create', $item))->assertOk();

        // Non-matching answer creates a pending claim for manual owner review
        $this->actingAs($claimant)
            ->post(route('claims.store', $item), [
                'answer' => 'Ada gantungan tali biru di tutupnya.',
            ])
            ->assertRedirect(route('items.show', $item));

        $claim = Claim::where('item_id', $item->id)->where('user_id', $claimant->id)->first();
        $this->assertNotNull($claim);
        $this->assertSame(Claim::STATUS_PENDING, $claim->status);

        // Duplicate pending claim by same user is blocked
        $this->actingAs($claimant)
            ->post(route('claims.store', $item), ['answer' => 'Coba lagi'])
            ->assertSessionHas('error');

        $otherClaim = Claim::factory()->for($item)->for($otherClaimant)->create([
            'status' => Claim::STATUS_PENDING,
        ]);

        // Non-owner cannot approve
        $this->actingAs($claimant)
            ->post(route('claims.approve', $claim))
            ->assertForbidden();

        // Owner approves first claim -> item resolved, other pending claim rejected
        $this->actingAs($owner)
            ->post(route('claims.approve', $claim))
            ->assertRedirect();

        $this->assertSame(Claim::STATUS_APPROVED, $claim->fresh()->status);
        $this->assertSame(Claim::STATUS_REJECTED, $otherClaim->fresh()->status);
        $this->assertSame(Item::STATUS_RESOLVED, $item->fresh()->status);
    }

    public function test_owner_can_reject_claim_and_use_found_button(): void
    {
        $owner = User::factory()->create();
        $claimant = User::factory()->create();

        $item = Item::factory()->for($owner)->lost()->create();
        $claim = Claim::factory()->for($item)->for($claimant)->create();

        // Owner cannot claim own item
        $this->actingAs($owner)->get(route('claims.create', $item))->assertRedirect(route('items.show', $item));
        $this->actingAs($owner)->post(route('claims.store', $item), ['answer' => 'milik sendiri'])->assertSessionHas('error');

        // Owner rejects claim
        $this->actingAs($owner)->post(route('claims.reject', $claim))->assertRedirect();
        $this->assertSame(Claim::STATUS_REJECTED, $claim->fresh()->status);

        // Non-owner cannot press Found button
        $this->actingAs($claimant)->post(route('items.found', $item))->assertForbidden();

        // Owner presses Found button -> item resolved
        $this->actingAs($owner)->post(route('items.found', $item))->assertRedirect();
        $this->assertSame(Item::STATUS_RESOLVED, $item->fresh()->status);

        // Resolved item cannot be claimed anymore
        $this->actingAs($claimant)->post(route('claims.store', $item), ['answer' => 'terlambat'])->assertSessionHas('error');
    }
}
