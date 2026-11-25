<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClaimSeeder extends Seeder
{
    /**
     * Seed sample claims on found items.
     */
    public function run(): void
    {
        $users = User::orderBy('id')->get();
        $foundItems = Item::found()->orderBy('id')->get();

        if ($users->count() < 2 || $foundItems->isEmpty()) {
            return;
        }

        foreach ($foundItems->take(2) as $index => $item) {
            $claimant = $users->firstWhere('id', '!=', $item->user_id);
            if (! $claimant) {
                continue;
            }

            Claim::firstOrCreate(
                [
                    'item_id' => $item->id,
                    'user_id' => $claimant->id,
                ],
                [
                    'answer' => $index === 0
                        ? 'Ada stiker logo FIK UPNVJ dan stiker kucing putih di bagian bawah.'
                        : 'Gantungan bebek warna kuning dengan tulisan FIK 25.',
                    'status' => Claim::STATUS_PENDING,
                ]
            );
        }
    }
}
