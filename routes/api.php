<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        require base_path('routes/api/v1/auth.php');
    });

    Route::prefix('election-sessions')->group(function () {
        require base_path('routes/api/v1/election_session.php');
    });

    Route::prefix('candidate-pairs')->group(function () {
        require base_path('routes/api/v1/candidate_pair.php');
    });

    Route::prefix('voters')->group(function () {
        require base_path('routes/api/v1/voter.php');
    });

    Route::prefix('users')->group(function () {
        require base_path('routes/api/v1/user.php');
    });
});
