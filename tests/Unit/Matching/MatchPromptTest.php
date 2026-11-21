<?php

namespace Tests\Unit\Matching;

use App\Services\Matching\ItemSnapshot;
use App\Services\Matching\MatchPrompt;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class MatchPromptTest extends TestCase
{
    private function snap(int $id): ItemSnapshot
    {
        return new ItemSnapshot($id, 'Tumbler', 'Tumbler hitam stiker bunga', 'Perpustakaan', Carbon::parse('2026-11-20'));
    }

    public function test_clean_menyamarkan_email_dan_nomor(): void
    {
        $this->assertSame(
            'hubungi [nomor] atau [email]',
            MatchPrompt::clean('hubungi 0812-3456-7890 atau budi@mail.com')
        );
    }

    public function test_parse_membuang_id_asing_dan_membatasi_skor(): void
    {
        $cands = collect([$this->snap(1), $this->snap(2)]);
        $json  = "```json\n[{\"id\":1,\"score\":150,\"reason\":\"mirip\"},{\"id\":99,\"score\":80},{\"id\":2,\"score\":-5}]\n```";

        $this->assertSame(
            [['id' => 1, 'score' => 100, 'reason' => 'mirip'], ['id' => 2, 'score' => 0, 'reason' => '']],
            MatchPrompt::parse($json, $cands)
        );
    }

    public function test_parse_melempar_exception_untuk_teks_bukan_json(): void
    {
        $this->expectException(\JsonException::class);
        MatchPrompt::parse('bukan json', collect([$this->snap(1)]));
    }
}