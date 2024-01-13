<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DogBreedService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // -------------------
        // | Added in project 
        // -------------------
        $this->app->bind(DogBreedService::class, function ($app) {
            $baseUrl = config('services.dogbreed.base_url');
            return new DogBreedService($baseUrl);
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        };
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
