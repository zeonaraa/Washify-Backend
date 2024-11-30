<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    OutletsRepositoryInterface,
    PaketRepositoryInterface
};
use App\Repositories\{
    OutletsRepository,
    PaketRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OutletsRepositoryInterface::class, OutletsRepository::class);
        $this->app->bind(PaketRepositoryInterface::class, PaketRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
