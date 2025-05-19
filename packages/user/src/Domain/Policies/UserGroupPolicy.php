<?php

namespace RedJasmine\User\Domain\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;
use RedJasmine\User\Domain\Models\UserGroup as Model;

class UserGroupPolicy
{
    use HandlesAuthorization;

    public static function getModel() : string
    {
        return Model::class;
    }

    use HasDefaultPolicy;
}
