<?php

namespace App\Repositories\Permission;

interface RoleRepository
{
    /**
     * Bulk insert roles
     *
     * @param array $roles
     * @return bool
     */
    public function bulkInsert(array $roles): bool;
}
