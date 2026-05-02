<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            $appUrl = rtrim((string) config('app.url'), '/');

            if ($appUrl !== '') {
                // Force Livewire signed upload URLs to match the public Railway URL.
                URL::forceRootUrl($appUrl);
            }

            URL::forceScheme('https');
        }
    }
}
