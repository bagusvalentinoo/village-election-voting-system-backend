<?php

namespace App\Repositories\Permission;

use App\Models\Permission\Role;

readonly class RoleRepositoryImpl implements RoleRepository
{
    public function __construct(private Role $role)
    {
        //
    }

    /**
     * Bulk insert roles
     *
     * @param array $roles
     * @return bool
     */
    public function bulkInsert(array $roles): bool
    {
        $now = now();

        return $this->role->insert(
            array_map(
                function ($role) use ($now) {
                    return array_filter([
                        'name' => $role['name'] ?? null,
                        'guard_name' => $role['guard_name'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now
                    ], isNotNullArrayFilter());
                },
                $roles
            )
        );
    }
}
