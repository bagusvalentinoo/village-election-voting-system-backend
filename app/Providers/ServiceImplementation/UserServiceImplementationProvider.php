<?php

namespace App\Providers\ServiceImplementation;

use App\Services\User\CandidatePairService;
use App\Services\User\CandidatePairServiceImpl;
use App\Services\User\UserService;
use App\Services\User\UserServiceImpl;
use App\Services\User\VoterService;
use App\Services\User\VoterServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceImplementationProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
        CandidatePairService::class => CandidatePairServiceImpl::class,
        VoterService::class => VoterServiceImpl::class,
        UserService::class => UserServiceImpl::class,
    ];

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            CandidatePairService::class,
            VoterService::class,
            UserService::class,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
