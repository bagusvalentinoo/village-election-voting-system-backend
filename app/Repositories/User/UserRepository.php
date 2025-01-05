<?php

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    /**
     * Get All Users
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAll(array $payload = []): Collection|Paginator;

    /**
     * Get User By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\User\User|null
     */
    public function getByParam(?string $param = null, array $payload = []): User|null;

    /**
     * Find user for login
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Builder|\App\Models\User\User|null
     */
    public function findForLogin(array $payload): User|Builder|null;

    /**
     * Create user
     *
     * @param array $payload
     * @return \App\Models\User\User
     */
    public function create(array $payload): User;

    /**
     * Update User By Param
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\User\User
     */
    public function updateByParam(string $param, array $payload): User;

    /**
     * Bulk Delete Users
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null;
}
