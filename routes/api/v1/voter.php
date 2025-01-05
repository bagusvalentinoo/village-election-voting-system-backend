<?php

use App\Helpers\Model\RoleHelper;
use App\Http\Controllers\User\VoterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VoterController::class, 'index'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::get('/{param}', [VoterController::class, 'show'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::post('/', [VoterController::class, 'store'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::post('/check-ongoing-available-otp', [VoterController::class, 'checkOngoingAndAvailableOtp']);
Route::post('/vote-candidate', [VoterController::class, 'voteCandidate']);

Route::patch('/{param}', [VoterController::class, 'update'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::delete('/', [VoterController::class, 'destroy'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);
