<?php

namespace RedJasmine\Admin\Domain\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Models\Role;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;

class RolePolicy
{
    use HandlesAuthorization;

    use HasDefaultPolicy;


    public function before(Admin $admin, $ability) : bool|null
    {
        if ($admin->isAdministrator()) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin $admin, Role $role) : bool
    {
        return true;
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin $admin) : bool
    {
        return true;

    }

}
