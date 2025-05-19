<?php

namespace RedJasmine\User\Domain\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;
use RedJasmine\User\Domain\Models\UserTag as Model;

class UserTagPolicy
{
    use HandlesAuthorization;

    public static function getModel() : string
    {
        return Model::class;
    }

    use HasDefaultPolicy;
}
