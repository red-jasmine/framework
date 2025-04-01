<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 互动统计
 * @property-read int $id
 * @property-read string $resource_type
 * @property-read string $resource_id
 * @property-read string $interaction_type
 * @property-read int $quantity
 *
 */
class InteractionStatistic extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use SoftDeletes;
}
