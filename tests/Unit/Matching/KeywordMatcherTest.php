<?php

namespace Tests\Unit\Matching;

use App\Services\Matching\ItemSnapshot;
use App\Services\Matching\KeywordMatcher;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class KeywordMatcherTest extends TestCase
{
    public function test_barang_mirip_mendapat_skor_lebih_tinggi(): void
    {
        $d    = Carbon::parse('2026-11-20');
        $lost = new ItemSnapshot(0, 'Tumbler hitam', 'Tumbler hitam stiker bunga', 'Perpustakaan', $d);
        $c1   = new ItemSnapshot(1, 'Tumbler gelap', 'Tumbler hitam stiker bunga', 'Perpus lt.2', $d);
        $c2   = new ItemSnapshot(2, 'Payung', 'Payung lipat biru', 'Kantin', $d);

        $r = (new KeywordMatcher)->score($lost, collect([$c1, $c2]));

        $this->assertSame(80, $r[0]['score']);
        $this->assertSame(0, $r[1]['score']);
    }
}