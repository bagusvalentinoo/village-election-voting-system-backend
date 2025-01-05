<?php

namespace Database\Seeders\User;

use App\Helpers\Model\RoleHelper;
use App\Repositories\Permission\RoleRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function __construct(private readonly RoleRepository $roleRepo)
    {
        //
    }

    /**
     * Run the database seeds.
     *
     * @throws \Throwable
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $this->roleRepo->bulkInsert(
                array_map(
                    function ($roleName) {
                        return [
                            'name' => $roleName,
                            'guard_name' => config('auth.defaults.guard')
                        ];
                    },
                    RoleHelper::ROLE_NAMES
                )
            );

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
