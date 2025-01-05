<?php

use App\Helpers\Model\RoleHelper;
use App\Http\Controllers\User\CandidatePairController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CandidatePairController::class, 'index']);

Route::get('/{param}', [CandidatePairController::class, 'show'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::post('/', [CandidatePairController::class, 'store'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::patch('/{param}', [CandidatePairController::class, 'update'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::delete('/', [CandidatePairController::class, 'destroy'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);
