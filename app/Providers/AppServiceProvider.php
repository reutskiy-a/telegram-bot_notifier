<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Api;
use Telegram\Bot\HttpClients\GuzzleHttpClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend(Api::class, function() {
            $guzzle = new Client(['verify' => false, 'debug' => true]);
            $httpClient = new GuzzleHttpClient($guzzle);
            $token = config('app.tg_bot_token');

            return new Api($token, false, $httpClient);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
