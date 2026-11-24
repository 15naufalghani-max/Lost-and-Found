<?php

namespace App\Console\Commands;

use App\Services\Matching\ItemSnapshot;
use App\Services\Matching\MatchProvider;
use Illuminate\Console\Command;

class AiPing extends Command
{
    protected $signature = 'ai:ping';

    protected $description = 'Tes koneksi dan format balasan penyedia AI untuk Smart Matching';

    public function handle(MatchProvider $ai): int
    {
        $mk = fn (int $id, string $t, string $d, string $l) => new ItemSnapshot($id, $t, $d, $l, now());

        $lost  = $mk(0, 'Tumbler hitam', 'Botol minum hitam dengan stiker bunga', 'Perpustakaan');
        $cands = collect([
            $mk(1, 'Tumbler gelap', 'Tumbler warna hitam, ada stiker bunga', 'Perpus lt.2'),
            $mk(2, 'Payung', 'Payung lipat biru', 'Kantin'),
        ]);

        try {
            $this->info('OK: '.json_encode($ai->score($lost, $cands)));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error(class_basename($e).': '.$e->getMessage());

            return self::FAILURE;
        }
    }
}