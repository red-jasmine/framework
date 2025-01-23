<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use Illuminate\Notifications\Notifiable;
use RedJasmine\User\Domain\Enums\UserTypeEnum;


class User extends Authenticatable
{
    public $incrementing = false;

    use HasSnowflakeId;

    use Notifiable;


    protected function casts() : array
    {
        // TODO 手机号、邮箱、 加密
        return [
            'type'     => UserTypeEnum::class,
            'status'   => UserStatusEnum::class,
            'password' => 'hashed',
        ];
    }
}
