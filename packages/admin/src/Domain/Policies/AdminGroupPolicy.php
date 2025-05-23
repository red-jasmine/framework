<?php

namespace RedJasmine\Admin\Domain\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;
use RedJasmine\User\Domain\Models\UserGroup as Model;

class AdminGroupPolicy
{
    use HandlesAuthorization;

    public static function getModel() : string
    {
        return Model::class;
    }

    use HasDefaultPolicy;
}
