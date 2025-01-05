<?php

namespace App\Services\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;

interface LoginService
{
    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function login(Request $request): array;

    /**
     * Logout
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function logout(Request $request): void;

    /**
     * Get user logged in
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\User
     */
    public function getUserLoggedIn(Request $request): User;
}
