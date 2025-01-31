<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use Illuminate\Notifications\Notifiable;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use RedJasmine\User\Domain\Events\UserLoginEvent;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
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

    protected $dispatchesEvents = [
        'login'=>UserLoginEvent::class,
        'register'=>UserRegisteredEvent::class
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() : array
    {
        return [
            'username' => $this->username,
            'nickname' => $this->nickname
        ];
    }

    public function checkPassword(string $password) : bool
    {

        // TODO  验证密码


        return true;
    }


    public function login():void
    {
        $this->fireModelEvent('login',false);
    }

}
