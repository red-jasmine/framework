<?php

namespace RedJasmine\Admin\Domain\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use RedJasmine\Admin\Domain\Models\Enums\AdminGenderEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminStatusEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminTypeEnum;
use RedJasmine\Support\Casts\AesEncrypted;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject, UserInterface, OperatorInterface, BelongsToOwnerInterface
{

    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;

    use HasRoles;

    use Notifiable;

    use HasOperator;

    use SoftDeletes;

    public function owner() : UserInterface
    {
        return UserData::from([
            'type' => 'system',
            'id'   => 'system',
        ]);
    }

    public function isAdmin() : bool
    {
        return true;
    }

    public function getType() : string
    {
        return 'admin';
    }

    public function getID() : string
    {
        return $this->getKey();
    }

    public function getNickname() : ?string
    {
        return $this->name;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() : array
    {
        return [
            'name' => $this->name,
        ];
    }


    protected function casts() : array
    {

        return [
            'phone'    => AesEncrypted::class,
            'email'    => AesEncrypted::class,
            'gender'   => AdminGenderEnum::class,
            'type'     => AdminTypeEnum::class,
            'status'   => AdminStatusEnum::class,
            'password' => 'hashed',
        ];
    }



    protected $fillable = [
        'email',
        'password',
        'name'
    ];
}
