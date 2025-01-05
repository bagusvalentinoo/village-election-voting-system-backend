<?php

use App\Helpers\Model\RoleHelper;
use App\Http\Controllers\Election\ElectionSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ElectionSessionController::class, 'index'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::get('/ongoing-for-voting', [ElectionSessionController::class, 'getOngoingForVoting']);
Route::get('/ongoing-for-result', [ElectionSessionController::class, 'getOngoingForResult'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);;

Route::get('/{param}', [ElectionSessionController::class, 'show'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::post('/', [ElectionSessionController::class, 'store'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::patch('/{param}', [ElectionSessionController::class, 'update'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::delete('/', [ElectionSessionController::class, 'destroy'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);
