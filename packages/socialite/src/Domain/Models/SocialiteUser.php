<?php

namespace RedJasmine\Socialite\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class SocialiteUser extends Model implements OwnerInterface
{


    public $incrementing = false;
    use HasSnowflakeId;
    use HasOwner;

    protected $fillable = [
        'app_id',
        'provider',
        'client_id',
        'identity',
        'owner_type',
        'owner_id',
    ];


    public function isBind() : bool
    {
        if (filled($this->owner_type) && filled($this->owner_id)) {
            return true;
        }
        return false;
    }

    public function isAllowBind() : bool
    {
        return !$this->isBind();
    }

    /**
     * 绑定
     *
     * @param  string  $appId
     * @param  UserInterface  $owner
     *
     * @return void
     */
    public function bind(string $appId, UserInterface $owner) : void
    {

        $this->isAllowBind();
        // 验证是否允许绑定
        $this->app_id     = $appId;
        $this->owner_type = $owner->getType();
        $this->owner_id   = (string) $owner->getID();
        $this->fireModelEvent('bind', false);
    }


    /**
     * 解绑
     * @return void
     */
    public function unbind() : void
    {
        $this->owner_type = null;
        $this->owner_id   = null;
        $this->fireModelEvent('unbind', false);

    }
}
