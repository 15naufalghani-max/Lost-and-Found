<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_catalog_and_item_detail(): void
    {
        $lost = Item::factory()->lost()->create(['title' => 'Dompet Hilang']);
        $found = Item::factory()->found()->create(['title' => 'Kunci Ditemukan']);

        $this->get(route('items.index'))
            ->assertOk()
            ->assertSee('Dompet Hilang')
            ->assertSee('Kunci Ditemukan');

        $this->get(route('items.index', ['type' => 'lost']))
            ->assertOk()
            ->assertSee('Dompet Hilang')
            ->assertDontSee('Kunci Ditemukan');

        $this->get(route('items.show', $lost))
            ->assertOk()
            ->assertSee('Dompet Hilang');
    }

    public function test_verified_user_can_create_item_with_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $tinyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

        $response = $this->actingAs($user)->post(route('items.store'), [
            'title' => 'Laptop Asus Vivobook',
            'type' => Item::TYPE_LOST,
            'category' => 'Elektronik',
            'location' => 'Lab Komputer FIK',
            'description' => 'Laptop warna silver tertinggal di meja barisan kedua.',
            'image' => UploadedFile::fake()->createWithContent('laptop.png', $tinyPng),
        ]);

        $item = Item::first();
        $this->assertNotNull($item);
        $response->assertRedirect(route('items.show', $item));
        $this->assertSame($user->id, $item->user_id);
        $this->assertSame(Item::STATUS_OPEN, $item->status);
        $this->assertNotNull($item->image);
        Storage::disk('public')->assertExists($item->image);
    }

    public function test_store_item_validates_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'title' => 'AB',
            'type' => 'invalid',
            'category' => '',
            'location' => '',
            'description' => 'pendek',
            'event_at' => now()->addDay()->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors(['title', 'type', 'category', 'location', 'description', 'event_at']);
    }

    public function test_only_owner_can_edit_update_and_delete_item(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $item = Item::factory()->for($owner)->create(['title' => 'Botol Minum']);

        $this->actingAs($other)->get(route('items.edit', $item))->assertForbidden();
        $this->actingAs($other)->put(route('items.update', $item), [
            'title' => 'Botol Diubah',
            'type' => $item->type,
            'category' => $item->category,
            'location' => $item->location,
            'description' => $item->description,
        ])->assertForbidden();
        $this->actingAs($other)->delete(route('items.destroy', $item))->assertForbidden();

        $this->actingAs($owner)->put(route('items.update', $item), [
            'title' => 'Botol Minum Biru',
            'type' => $item->type,
            'category' => $item->category,
            'location' => $item->location,
            'description' => 'Deskripsi baru yang lebih lengkap untuk botol minum.',
            'status' => Item::STATUS_RESOLVED,
        ])->assertRedirect(route('items.show', $item));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'title' => 'Botol Minum Biru',
            'status' => Item::STATUS_RESOLVED,
        ]);

        $this->actingAs($owner)
            ->delete(route('items.destroy', $item))
            ->assertRedirect(route('items.index'));

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
