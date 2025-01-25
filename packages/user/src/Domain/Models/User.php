<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use Illuminate\Notifications\Notifiable;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims():array
    {
        return [
            'username' => $this->username,
            'nickname' => $this->nickname
        ];
    }


}
