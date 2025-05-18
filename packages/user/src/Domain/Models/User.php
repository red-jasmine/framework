<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\User\Domain\Data\UserBaseInfoData;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use RedJasmine\User\Domain\Events\UseCancelEvent;
use RedJasmine\User\Domain\Events\UserLoginEvent;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject, UserInterface,OperatorInterface
{


    use HasOperator;

    protected  $withOperatorNickname = true;

    public $incrementing = false;

    use HasSnowflakeId;

    use Notifiable;

    protected $dispatchesEvents = [
        'login'    => UserLoginEvent::class,
        'register' => UserRegisteredEvent::class,
        'cancel'   => UseCancelEvent::class,
    ];

    protected static function boot() : void
    {
        parent::boot();


        static::saving(function (User $user) {
            if ($user->relationLoaded('tags')) {

                if ((is_array($user->tags) ? count($user->tags) : ($user->tags?->count())) > 0) {
                    if (is_array($user->tags)) {

                        $user->tags()->sync($user->tags);
                    } elseif (!is_array($user->tags->first())) {
                        $user->tags()->sync(($user->tags));
                    } else {

                        $user->tags()->sync($user->tags->pluck('id')->toArray());
                    }

                } else {
                    $user->tags()->sync([]);
                }
                $user->load('tags');
            }
        });

    }

    public function tags() : BelongsToMany
    {

        return $this->belongsToMany(
            UserTag::class,
            'user_tag_pivot',
            'user_id',
            'user_tag_id'
        )->withTimestamps();
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
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


    public function isAllowActivity() : bool
    {
        if ($this->status !== UserStatusEnum::ACTIVATED) {
            return false;
        }
        return true;
    }

    public function isAdmin() : bool
    {
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
        return $this->belongsTo(UserGroup::class, 'group_id', 'id');
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

    protected function casts() : array
    {

        return [
            'phone'       => AesEncrypted::class,
            'email'       => AesEncrypted::class,
            'gender'      => UserGenderEnum::class,
            'type'        => UserTypeEnum::class,
            'status'      => UserStatusEnum::class,
            'password'    => 'hashed',
            'cancel_time' => 'datetime',
        ];
    }


}
