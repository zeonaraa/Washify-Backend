<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    OutletsRepositoryInterface,
    PaketRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    OutletsRepository,
    PaketRepository,
    UserRepository
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
