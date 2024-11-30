<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\OutletsRepositoryInterface;
use App\Repositories\OutletsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OutletsRepositoryInterface::class, OutletsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
