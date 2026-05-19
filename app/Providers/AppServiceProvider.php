<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('apple', \SocialiteProviders\Apple\Provider::class);
        });

        // TrustProxies(at: '*') di bootstrap/app.php sudah memproses header
        // X-Forwarded-Host dan X-Forwarded-Proto dari ngrok, sehingga
        // getSchemeAndHttpHost() mengembalikan URL ngrok yang benar.
        $baseUrl = request()->getSchemeAndHttpHost();

        if (str_contains($baseUrl, 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceRootUrl($baseUrl);
        }
    }
}
