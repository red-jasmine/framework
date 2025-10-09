<?php

namespace RedJasmine\UserCore\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Casts\AesEncrypted;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\UserCore\Domain\Data\UserBaseInfoData;
use RedJasmine\UserCore\Domain\Enums\AccountTypeEnum;
use RedJasmine\UserCore\Domain\Enums\UserGenderEnum;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, UserInterface, OperatorInterface
{

    use HasOperator;

    use SoftDeletes;

    use HasSnowflakeId;

    use Notifiable;


    public    $incrementing         = false;
    protected $withOperatorNickname = true;


    protected $fillable = [
        'email',
        'name',
        'nickname',
        'password',
    ];


    public function isAdmin()
    {
        return true;
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
        }
        return $instance;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() : array
    {
        return [
            'type'     => $this->getType(),
            'name'     => $this->name,
            'nickname' => $this->nickname
        ];
    }

    public function getType() : string
    {
        return 'user';
    }

    public function login() : void
    {
        $this->fireModelEvent('login', false);
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

    public function isAllowActivity() : bool
    {
        if ($this->status !== UserStatusEnum::ACTIVATED) {
            return false;
        }
        return true;
    }

    public function setPassword(string $password) : void
    {
        $this->password            = $password;
        $this->password_updated_at = Carbon::now();
    }

    public function setStatus(UserStatusEnum $status) : void
    {
        $this->status = $status;
    }

    public function changePhone(string $phone) : void
    {
        $this->phone = $phone;
    }

    public function changeEmail(string $email) : void
    {
        $this->email = $email;
    }


    /**
     * 注销账户
     * @return void
     */
    public function cancel() : void
    {
        $this->status = UserStatusEnum::CANCELED;

        $this->cancel_time = Carbon::now();

        $this->fireModelEvent('cancel', false);

    }

    public function register() : void
    {
        $this->fireModelEvent('register', false);
    }

    /**
     * @return Attribute
     */
    public function inviter() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->inviter_type && $this->inviter_id) ? UserData::from([
                'type'     => $this->inviter_type,
                'id'       => $this->inviter_id,
                'nickname' => $this->inviter_nickname ?? null,
            ]) : null,
            set: fn(?UserInterface $user = null) => [
                'inviter_type'     => $user?->getType(),
                'inviter_id'       => $user?->getID(),
                'inviter_nickname' => $user?->getNickname(),
            ],
        );
    }

    public function getID() : string
    {
        return $this->getKey();
    }

    public function getNickname() : ?string
    {
        return $this->nickname;
    }


    public function getUserData() : array
    {
        return [
            'type'     => $this->getType(),
            'id'       => $this->getID(),
            'nickname' => $this->getNickname(),
            'avatar'   => $this->getAvatar(),
        ];
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }

    protected function casts() : array
    {

        return [
            'phone'        => AesEncrypted::class,
            'email'        => AesEncrypted::class,
            'gender'       => UserGenderEnum::class,
            'account_type' => AccountTypeEnum::class,
            'status'       => UserStatusEnum::class,
            'password'     => 'hashed',
            'cancel_time'  => 'datetime',
        ];
    }


}
