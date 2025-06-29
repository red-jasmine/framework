<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasTags;
use RedJasmine\User\Domain\Data\UserBaseInfoData;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\AccountTypeEnum;
use RedJasmine\User\Domain\Events\UseCancelEvent;
use RedJasmine\User\Domain\Events\UserLoginEvent;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * @property string $phone
 * @property string $name
 * @property string $invitation_code
 */
class User extends Authenticatable implements JWTSubject, UserInterface, OperatorInterface
{

    use HasOperator;

    public static string $tagModelClass = UserTag::class;
    public static string $tagTable      = 'user_tag_pivot';

    use HasSnowflakeId;

    use Notifiable;


    use HasTags;

    public static string $groupModelClass      = UserGroup::class;
    public               $incrementing         = false;
    protected            $withOperatorNickname = true;

    protected $dispatchesEvents = [
        'login'    => UserLoginEvent::class,
        'register' => UserRegisteredEvent::class,
        'cancel'   => UseCancelEvent::class,
    ];

    protected $fillable = [
        'email',
        'name',
        'nickname',
        'password',
    ];

    protected static function boot() : void
    {
        parent::boot();
    }

    public function isAdmin()
    {
        return true;
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('tags', Collection::make());

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
            'name'     => $this->name,
            'nickname' => $this->nickname
        ];
    }

    public function login() : void
    {
        $this->fireModelEvent('login', false);
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

    public function isAllowActivity() : bool
    {
        if ($this->status !== UserStatusEnum::ACTIVATED) {
            return false;
        }
        return true;
    }

    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    public function setGroup(?int $groupId = null) : void
    {
        $this->group_id = $groupId;
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

    public function group() : BelongsTo
    {
        return $this->belongsTo(static::$groupModelClass, 'group_id', 'id');
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

    public function getInvitationCode() : string
    {
        return $this->invitation_code;
    }

    public function setInvitationCode(string $invitation_code) : static
    {
        $this->invitation_code = $invitation_code;
        return $this;
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
