<?php

namespace App\Providers;

use App\Services\Matching\GeminiMatchProvider;
use App\Services\Matching\MatchProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MatchProvider::class, GeminiMatchProvider::class);
    }

    public function boot(): void
    {
        //
    }
}