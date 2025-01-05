<?php

use App\Helpers\Model\RoleHelper;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::get('/{param}', [UserController::class, 'show'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::post('/', [UserController::class, 'store'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::patch('/{param}', [UserController::class, 'update'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);

Route::delete('/', [UserController::class, 'destroy'])
    ->middleware(['auth:api', 'role:' . RoleHelper::ROLE_NAMES['petugas']]);
