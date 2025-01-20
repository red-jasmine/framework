<?php

namespace RedJasmine\Socialite\Domain\Models;

use Illuminate\Database\Eloquent\Model;
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
        'provider_code',
        'client_id',
        'identity',
        'user_type',
        'user_id',
    ];
}
