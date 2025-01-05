<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

readonly class UserServiceImpl implements UserService
{
    public function __construct(private UserRepository $userRepo)
    {
        //
    }

    /**
     * Get All Users
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllUsers(Request $request): Collection|Paginator
    {
        return $this->userRepo->getAll([
            'paginate' => $request->input('paginate') == 'true',
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page')
        ]);
    }

    /**
     * Get User By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\User|null
     */
    public function getUserByParam(Request $request, string $param): User|null
    {
        return $this->userRepo->getByParam($param, [
            'fail' => true
        ]);
    }

    /**
     * Create User
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\User
     * @throws \Throwable
     */
    public function createUser(Request $request): User
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepo->create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
            ]);

            $user->assignRole($request->input('role_ids'));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $user;
    }

    /**
     * Update User By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateUserByParam(Request $request, string $param): void
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepo->updateByParam($param, [
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'password' => $request->has('password') ? Hash::make($request->input('password')) : null
            ]);

            if ($request->has('role_ids')) $user->syncRoles($request->input('role_ids'));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Bulk Delete Users
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteUsers(Request $request): void
    {
        $users = $this->userRepo->getAll([
            'paginate' => false,
            'ids' => $request->input('ids'),
        ]);

        try {
            DB::beginTransaction();

            foreach ($users as $user) $user->syncRoles([]);

            $this->userRepo->bulkDelete([
                'ids' => $users->pluck('id')->toArray()
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
