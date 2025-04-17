<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Data\UserBaseInfoData;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
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
            'gender'   => UserGenderEnum::class,
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
            'name'     => $this->name,
            'nickname' => $this->nickname
        ];
    }


    public function login() : void
    {
        $this->fireModelEvent('login', false);
    }

    public function getType() : string
    {
        return 'user';
    }

    public function getID() : string
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
        $attributes = [
            'nickname',
            'avatar',
            'gender',
            'birthday',
            'biography',
            'country',
            'province',
            'city',
            'district',
            'school',
        ];
        foreach ($attributes as $attribute) {
            if (isset($data->{$attribute})) {
                $this->{$attribute} = $data->{$attribute};
            }
        }
    }

    public function isAdmin() : bool
    {
        return true;

    }

    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

}
