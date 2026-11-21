<?php

namespace Tests\Unit\Matching;

use App\Services\Matching\GeminiMatchProvider;
use App\Services\Matching\ItemSnapshot;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiMatchProviderTest extends TestCase
{
    private function lost(): ItemSnapshot
    {
        return new ItemSnapshot(0, 'Tumbler hitam', 'Botol minum hitam stiker bunga', 'Perpustakaan', Carbon::parse('2026-11-20'));
    }

    private function cand(int $id): ItemSnapshot
    {
        return new ItemSnapshot($id, 'Tumbler gelap', 'Tumbler hitam stiker bunga', 'Perpus lt.2', Carbon::parse('2026-11-21'));
    }

    public function test_balasan_valid_diparse(): void
    {
        config(['services.gemini.key' => 'dummy', 'services.gemini.model' => 'model-uji']);
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => '[{"id":1,"score":90,"reason":"mirip"}]']]]]],
        ], 200)]);

        $out = (new GeminiMatchProvider)->score($this->lost(), collect([$this->cand(1)]));

        $this->assertSame(90, $out[0]['score']);
    }

    public function test_status_429_melempar_exception(): void
    {
        config(['services.gemini.key' => 'dummy', 'services.gemini.model' => 'model-uji']);
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response([], 429)]);

        $this->expectException(RequestException::class);
        (new GeminiMatchProvider)->score($this->lost(), collect([$this->cand(1)]));
    }

    public function test_tanpa_key_melempar_exception(): void
    {
        config(['services.gemini.key' => null]);

        $this->expectException(\RuntimeException::class);
        (new GeminiMatchProvider)->score($this->lost(), collect([$this->cand(1)]));
    }
}