<?php

namespace App\Providers;

use App\Services\Matching\GeminiMatchProvider;
use App\Services\Matching\MatchProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MatchProvider::class, GeminiMatchProvider::class);
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $data = $view->getData();

            $view->with([
                'menu' => $data['menu'] ?? [
                    ['label' => 'Beranda', 'url' => url('/')],
                    ['label' => 'Barang Hilang', 'url' => url('/barang-hilang')],
                    ['label' => 'Barang Temuan', 'url' => url('/barang-temuan')],
                ],
                'halaman' => $data['halaman'] ?? [
                    'masuk' => null,
                ],
                'footer' => $data['footer'] ?? [
                    'Navigasi' => [
                        ['label' => 'Beranda', 'url' => url('/')],
                        ['label' => 'Barang Hilang', 'url' => null],
                        ['label' => 'Barang Temuan', 'url' => null],
                    ],
                    'Bantuan' => [
                        ['label' => 'Cara Melapor', 'url' => null],
                        ['label' => 'Prosedur Klaim', 'url' => null],
                    ],
                ],
            ]);
        });
    }
}