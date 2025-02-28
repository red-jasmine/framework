<?php

namespace RedJasmine\Vip\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Vip\Domain\Models\Enums\VipStatusEnum;

class Vip extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;
    use HasSnowflakeId;
    use HasOperator;
    use HasOwner;

    protected function casts() : array
    {
        return [
            'status' => VipStatusEnum::class,
            'extras' => 'array'
        ];
    }

    protected $fillable = [
        'app_id',
        'type',
        'level',
        'name',
        'icon',
        'description',
        'status',
        'extras'
    ];
}
