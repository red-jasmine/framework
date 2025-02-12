<?php

namespace RedJasmine\ResourceUsage\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\ResourceUsage\Domain\Models\Enums\ResourceUsageStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ResourceUsage extends Model implements OwnerInterface, OperatorInterface
{
    public $incrementing = false;

    public $uniqueShortId = false;

    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    protected function casts() : array
    {
        return [
            'status' => ResourceUsageStatusEnum::class,
        ];
    }
}
