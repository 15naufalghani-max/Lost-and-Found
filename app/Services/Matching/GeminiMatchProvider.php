<?php

namespace App\Services\Matching;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GeminiMatchProvider implements MatchProvider
{
    public function score(ItemSnapshot $lost, Collection $candidates): array
    {
        $key   = config('services.gemini.key');
        $model = config('services.gemini.model');

        if (blank($key)) {
            throw new \RuntimeException('GEMINI_API_KEY belum diisi di .env');
        }

        $res = Http::withHeaders(['x-goog-api-key' => $key])
            ->connectTimeout(5)->timeout(10)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'systemInstruction' => ['parts' => [['text' => MatchPrompt::SYSTEM]]],
                'contents' => [[
                    'role'  => 'user',
                    'parts' => [['text' => json_encode(MatchPrompt::payload($lost, $candidates), JSON_UNESCAPED_UNICODE)]],
                ]],
                'generationConfig' => ['temperature' => 0.1, 'maxOutputTokens' => 2048],
            ])->throw();

        $text = collect($res->json('candidates.0.content.parts', []))
            ->reject(fn ($p) => ! empty($p['thought']))
            ->pluck('text')
            ->implode('');

        return MatchPrompt::parse($text, $candidates);
    }
}