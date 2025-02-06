<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Data\UserBaseInfoData;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use Illuminate\Notifications\Notifiable;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use RedJasmine\User\Domain\Events\UserLoginEvent;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject, UserInterface
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
        'login'    => UserLoginEvent::class,
        'register' => UserRegisteredEvent::class
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


    public function login() : void
    {
        $this->fireModelEvent('login', false);
    }

    public function getType() : string
    {
        return 'user';
    }

    public function getID() : int
    {
        return $this->getKey();
    }

    public function getNickname() : ?string
    {
        return $this->nickname;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }


    public function setUserBaseInfo(UserBaseInfoData $data) : void
    {
        isset($data->nickname) ? $this->nickname = $data->nickname : null;
        isset($data->avatar) ? $this->avatar = $data->avatar : null;
        isset($data->gender) ? $this->gender = $data->gender : null;
        isset($data->birthday) ? $this->birthday = $data->birthday : null;
        isset($data->biography) ? $this->biography = $data->biography : null;
    }


    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

}
