<?php

namespace Tests\Unit;

use App\Models\Claim;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use App\Services\Matching\ItemSnapshot;
use Carbon\CarbonInterface;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_relationships_casts_and_item_snapshot_compatibility(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(MustVerifyEmail::class, $user);

        $item = Item::factory()->for($user)->found()->create();
        $comment = Comment::factory()->for($item)->for($user)->create();
        $claim = Claim::factory()->for($item)->for($user)->create();

        $this->assertInstanceOf(CarbonInterface::class, $item->event_at);
        $this->assertTrue($user->items->contains($item));
        $this->assertTrue($user->comments->contains($comment));
        $this->assertTrue($user->claims->contains($claim));
        $this->assertTrue($item->comments->contains($comment));
        $this->assertTrue($item->claims->contains($claim));
        $this->assertSame($item->id, $comment->item->id);
        $this->assertSame($item->id, $claim->item->id);

        $snapshot = ItemSnapshot::from($item);
        $this->assertSame($item->id, $snapshot->id);
        $this->assertSame($item->title, $snapshot->title);
    }

    public function test_database_seeder_runs_cleanly_and_is_idempotent(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'kenny@mahasiswa.upnvj.ac.id', 'nim' => '2510512027']);
        $this->assertDatabaseHas('users', ['email' => 'dosen@dosen.upnvj.ac.id']);
        $this->assertGreaterThanOrEqual(6, Item::count());
        $this->assertGreaterThanOrEqual(3, Comment::count());
        $this->assertGreaterThanOrEqual(2, Claim::count());
    }
}
