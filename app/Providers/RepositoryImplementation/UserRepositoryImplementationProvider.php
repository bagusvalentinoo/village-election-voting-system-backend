<?php

namespace App\Providers\RepositoryImplementation;

use App\Repositories\Permission\RoleRepository;
use App\Repositories\Permission\RoleRepositoryImpl;
use App\Repositories\User\CandidatePairRepository;
use App\Repositories\User\CandidatePairRepositoryImpl;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryImpl;
use App\Repositories\User\VoterRepository;
use App\Repositories\User\VoterRepositoryImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserRepositoryImplementationProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
        RoleRepository::class => RoleRepositoryImpl::class,
        UserRepository::class => UserRepositoryImpl::class,
        CandidatePairRepository::class => CandidatePairRepositoryImpl::class,
        VoterRepository::class => VoterRepositoryImpl::class,
    ];

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            RoleRepository::class,
            UserRepository::class,
            CandidatePairRepository::class,
            VoterRepository::class,
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
