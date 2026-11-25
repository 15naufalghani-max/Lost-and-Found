<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_store_comment_and_validation_works(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $item), ['body' => ''])
            ->assertSessionHasErrors('body');

        $this->actingAs($user)
            ->post(route('comments.store', $item), ['body' => 'Sepertinya saya melihat barang ini di ruang 201.'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'body' => 'Sepertinya saya melihat barang ini di ruang 201.',
        ]);
    }

    public function test_comment_author_and_item_owner_can_delete_comment_but_stranger_cannot(): void
    {
        $itemOwner = User::factory()->create();
        $author = User::factory()->create();
        $stranger = User::factory()->create();

        $item = Item::factory()->for($itemOwner)->create();
        $comment1 = Comment::factory()->for($item)->for($author)->create();
        $comment2 = Comment::factory()->for($item)->for($author)->create();

        $this->actingAs($stranger)
            ->delete(route('comments.destroy', $comment1))
            ->assertForbidden();

        $this->actingAs($author)
            ->delete(route('comments.destroy', $comment1))
            ->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment1->id]);

        $this->actingAs($itemOwner)
            ->delete(route('comments.destroy', $comment2))
            ->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment2->id]);
    }
}
