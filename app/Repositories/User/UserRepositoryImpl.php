<?php

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

readonly class UserRepositoryImpl implements UserRepository
{
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Get All Users
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAll(array $payload = []): Collection|Paginator
    {
        $users = $this->user->baseFilter($payload);

        if (isset($payload['paginate']) && $payload['paginate'])
            return $users->simplePaginate($payload['per_page'] ?? null);

        return $users->get();
    }

    /**
     * Get User By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\User\User|null
     */
    public function getByParam(?string $param = null, array $payload = []): User|null
    {
        $user = $this->user->when($param, function ($query) use ($param) {
            return $query->where('id', $param);
        });

        if ($payload['fail'] ?? false) return $user->firstOrFail();
        return $user->first();
    }

    /**
     * Find user for login
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Builder|\App\Models\User\User|null
     */
    public function findForLogin(array $payload): User|Builder|null
    {
        return $this->user
            ->where('username', $payload['username'])
            ->with([
                'roles' => function ($query) {
                    return $query->select([
                        'id', 'name'
                    ]);
                }
            ])
            ->first();
    }

    /**
     * Create user
     *
     * @param array $payload
     * @return \App\Models\User\User
     */
    public function create(array $payload): User
    {
        return $this->user->create(
            array_filter([
                'name' => $payload['name'] ?? null,
                'username' => $payload['username'] ?? null,
                'password' => $payload['password'] ?? null
            ], isNotNullArrayFilter())
        );
    }

    /**
     * Update User By Param
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\User\User
     */
    public function updateByParam(string $param, array $payload): User
    {
        $user = $this->getByParam($param, ['fail' => true]);

        $user->update(
            array_filter([
                'name' => $payload['name'] ?? null,
                'username' => $payload['username'] ?? null,
                'password' => $payload['password'] ?? null
            ], isNotNullArrayFilter())
        );

        return $user->refresh();
    }

    /**
     * Bulk Delete Users
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null
    {
        return $this->user->whereIn('id', $payload['ids'])->delete();
    }
}
