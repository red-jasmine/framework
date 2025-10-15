<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\Traits\HasTags;
use RedJasmine\User\Domain\Events\UseCancelEvent;
use RedJasmine\User\Domain\Events\UserLoginEvent;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
use RedJasmine\UserCore\Domain\Models\User as UserCoreUser;

/**
 * @property string $phone
 * @property string $name
 * @property string $invitation_code
 */
class User extends UserCoreUser
{
    use HasTags;

    public static string $tagModelClass = UserTag::class;
    public static string $tagTable      = 'user_tag_pivot';
    public static string $groupModelClass = UserGroup::class;

    protected $dispatchesEvents = [
        'login'    => UserLoginEvent::class,
        'register' => UserRegisteredEvent::class,
        'cancel'   => UseCancelEvent::class,
    ];



    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('tags', Collection::make());
        }
        return $instance;
    }

    public function setGroup(?int $groupId = null) : void
    {
        $this->group_id = $groupId;
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(static::$groupModelClass, 'group_id', 'id');
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
}
