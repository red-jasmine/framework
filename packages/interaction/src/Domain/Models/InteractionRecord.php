<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property string $resource_type
 * @property string $resource_id
 *
 */
class InteractionRecord extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use SoftDeletes;
}
