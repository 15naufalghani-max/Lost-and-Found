<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

    public function test_many_to_many_attach_and_detach(): void
    {
        $item = Item::factory()->for(User::factory()->create())->found()->create();
        $user = User::factory()->create();

        $user->claimedItems()->attach($item->id, ['answer' => 'x', 'status' => Claim::STATUS_PENDING]);
        $this->assertTrue($item->claimants->contains($user));

        $user->claimedItems()->detach($item->id);
        $this->assertCount(0, $item->fresh()->claimants);
    }

    public function test_claimant_sees_cancel_button_on_item_page(): void
    {
        $item = Item::factory()->for(User::factory()->create())->found()->create();
        $claimant = User::factory()->create();
        Claim::factory()->for($item)->for($claimant)->create(['status' => Claim::STATUS_PENDING]);

        $this->actingAs($claimant)->get(route('items.show', $item))->assertSee('Batalkan klaim');
    }

    public function test_proof_is_stored_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $item = Item::factory()->for(User::factory()->create())->found()->create();

        $this->actingAs(User::factory()->create())->post(route('claims.store', $item), [
            'answer' => 'jawaban saya',
            'proof_image' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect();

        $path = Claim::firstOrFail()->proof_image;
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_claim_on_lost_item_is_rejected(): void
    {
        $item = Item::factory()->for(User::factory()->create())->lost()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('claims.store', $item), ['answer' => 'jawaban saya'])
            ->assertSessionHas('error');

        $this->assertSame(0, Claim::count());
    }

    public function test_owner_sees_review_buttons_for_pending_claim(): void
    {
        $owner = User::factory()->create();
        $item = Item::factory()->for($owner)->found()->create();
        Claim::factory()->for($item)->for(User::factory()->create())->create(['status' => Claim::STATUS_PENDING]);

        $this->actingAs($owner)->get(route('items.show', $item))
            ->assertSee('Setujui')->assertSee('Tolak');
    }
}