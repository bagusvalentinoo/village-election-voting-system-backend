<?php

namespace Database\Seeders\User;

use App\Helpers\Model\RoleHelper;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function __construct(private readonly UserRepository $userRepo)
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

            $user = $this->userRepo->create([
                'name' => 'Petugas 01',
                'username' => 'petugas01',
                'password' => 'petugas01'
            ]);

            $user->assignRole(RoleHelper::ROLE_NAMES['petugas']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
