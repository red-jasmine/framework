<?php

namespace RedJasmine\User\Domain\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\User\Domain\Models\UserTagCategory;

class UserTagCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny($user) : bool
    {

        return $user->can('view_any_user::tag::category');

    }

    public function view($user, UserTagCategory $userTagCategory) : bool
    {
        return $user->can('view_any_user::tag::category');
    }

    public function create($user) : bool
    {
        return $user->can('create_user::tag::category');
    }

    public function update($user, UserTagCategory $userTagCategory) : bool
    {
    }

    public function delete($user, UserTagCategory $userTagCategory) : bool
    {
    }

    public function restore($user, UserTagCategory $userTagCategory) : bool
    {
    }

    public function forceDelete($user, UserTagCategory $userTagCategory) : bool
    {
    }
}
