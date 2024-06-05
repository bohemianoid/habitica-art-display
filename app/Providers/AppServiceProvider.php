<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('habitica', function () {
            return Http::withHeaders([
                'X-Client' => config('habitica.author_id').'-'.config('app.name'),
            ])->baseUrl('https://habitica.com/api/v3');
        });

        Http::macro('openai', function () {
            return Http::baseUrl('https://api.openai.com/v1');
        });
    }
}
