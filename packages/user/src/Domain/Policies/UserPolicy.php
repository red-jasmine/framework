<?php

namespace RedJasmine\User\Domain\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\User\Domain\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function setStatus($user, User $model) : bool
    {
        return $user->can('setStatus_user');
    }

    public function viewAny($user) : bool
    {
        return true;
    }

    public function view($user, User $model) : bool
    {
        return true;
    }

    public function create($user) : bool
    {
        return true;
    }

    public function update($user, User $model) : bool
    {
        return true;
    }

    public function delete($user, User $model) : bool
    {
        return true;
    }

    public function restore($user, User $model) : bool
    {
        return true;
    }

    public function forceDelete($user, User $model) : bool
    {
        return true;
    }
}
