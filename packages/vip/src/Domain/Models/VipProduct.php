<?php

namespace RedJasmine\Vip\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use RedJasmine\Vip\Domain\Models\Enums\VipProductStatusEnum;

/**
 * @property int $id;
 * @property string $name
 * @property Money $price
 * @property TimeUnitEnum $time_unit
 * @property int $time_value
 */
class VipProduct extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;
    use HasSnowflakeId;
    use HasOperator;
    use HasOwner;

    protected function casts() : array
    {
        return [
            'status'    => VipProductStatusEnum::class,
            'price'     => MoneyCast::class,
            'time_unit' => TimeUnitEnum::class,
        ];
    }

    protected $fillable = [
        'app_id',
        'type',
        'name',
        'description',
        'status',
        'time_value',
        'time_unit',
        'price'
    ];
}
