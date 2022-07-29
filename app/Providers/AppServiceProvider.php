<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kerox\OAuth2\Client\Provider\Spotify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->bind(Spotify::class, function () {
            return new Spotify([
                'clientId' => config('services.spotify.client_id'),
                'clientSecret' => config('services.spotify.client_secret'),
                'redirectUri' => route('spotify')
            ]);
        });
    }
}
