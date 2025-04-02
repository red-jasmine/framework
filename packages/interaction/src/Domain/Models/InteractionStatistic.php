<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 互动统计
 * @property-read int $id
 * @property string $resource_type
 * @property string $resource_id
 * @property string $interaction_type
 * @property int $quantity
 *
 */
class InteractionStatistic extends Model
{

    public $incrementing = false;


    protected $fillable = [
        'resource_type',
        'resource_id',
        'interaction_type',
        'quantity'
    ];

    use HasSnowflakeId;
}
