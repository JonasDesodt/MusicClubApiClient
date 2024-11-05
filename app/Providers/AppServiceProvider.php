<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Http::macro('custom', function () {
            return Http::withHeaders([
                'Accept-Language' => app()->getLocale(),
                'Application-Name' => 'Laravel',
                'Api-Key' => 'NQGWMiK9/kGzUp9Qf9TagM2d0uyUEPT6RY5Qx0Eq0zY='
                // 'Application-Name' => 'ASPMVC',
                // 'Api-Key' => 'bcwAf6KNc0aTLLE9a2DgsK8FrhoVf+ZbnMKhGn1leYg='
            ]);
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
