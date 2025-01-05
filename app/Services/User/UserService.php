<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface UserService
{
    /**
     * Get All Users
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllUsers(Request $request): Collection|Paginator;

    /**
     * Get User By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\User|null
     */
    public function getUserByParam(Request $request, string $param): User|null;

    /**
     * Create User
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\User
     */
    public function createUser(Request $request): User;

    /**
     * Update User
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateUserByParam(Request $request, string $param): void;

    /**
     * Bulk Delete Users
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteUsers(Request $request): void;
}
