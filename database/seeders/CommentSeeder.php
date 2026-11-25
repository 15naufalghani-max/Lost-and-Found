<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Seed sample comments on Lost & Found items.
     */
    public function run(): void
    {
        $users = User::orderBy('id')->get();
        $items = Item::orderBy('id')->get();

        if ($users->isEmpty() || $items->isEmpty()) {
            return;
        }

        $samples = [
            'Tadi saya sempat lihat di dekat meja asisten lab, coba cek ke petugas lab ya.',
            'Apakah masih ada di ruang BEM FIK? Nanti siang saya mampir untuk cek.',
            'Terima kasih sudah mengamankan barangnya!',
        ];

        foreach ($items->take(3) as $index => $item) {
            $commenter = $users[($index + 1) % $users->count()];
            $body = $samples[$index % count($samples)];

            Comment::firstOrCreate([
                'item_id' => $item->id,
                'user_id' => $commenter->id,
                'body' => $body,
            ]);
        }
    }
}
