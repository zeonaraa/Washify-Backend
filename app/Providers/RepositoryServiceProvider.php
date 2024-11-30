<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    OutletsRepositoryInterface,
    PaketRepositoryInterface,
    UserRepositoryInterface,
    MemberRepositoryInterface,
    TransaksiRepositoryInterface
};
use App\Repositories\{
    OutletsRepository,
    PaketRepository,
    UserRepository,
    MemberRepository,
    TransaksiRepository
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
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(TransaksiRepositoryInterface::class, TransaksiRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
