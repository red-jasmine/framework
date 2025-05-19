<?php

namespace RedJasmine\User\Domain\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;
use RedJasmine\User\Domain\Models\User as Model;

class UserPolicy
{
    use HandlesAuthorization;

    use HasDefaultPolicy;

    public static function getModel() : string
    {
        return Model::class;
    }

    public function setAccount($user, Model $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function setGroup($user, Model $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function setStatus($user, Model $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }


}
