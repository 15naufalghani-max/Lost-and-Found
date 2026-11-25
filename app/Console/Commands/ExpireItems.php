<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class ExpireItems extends Command
{
    protected $signature = 'items:expire';

    protected $description = 'Tandai laporan barang yang melewati batas waktu sebagai expired';

    public function handle(): int
    {
        $count = Item::expireOverdue();

        $this->info("Berhasil menandai {$count} laporan kadaluwarsa.");

        return self::SUCCESS;
    }
}
