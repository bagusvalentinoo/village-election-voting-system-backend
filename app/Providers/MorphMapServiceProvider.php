<?php

namespace App\Providers;

use App\Models\Election\ElectionResult;
use App\Models\Election\ElectionSession;
use App\Models\Permission\Role;
use App\Models\User\CandidatePair;
use App\Models\User\User;
use App\Models\User\Voter;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::morphMap([
            'roles' => Role::class,
            'users' => User::class,
            'voters' => Voter::class,
            'candidate_pairs' => CandidatePair::class,

            'election_sessions' => ElectionSession::class,
            'election_results' => ElectionResult::class,
        ]);
    }
}
