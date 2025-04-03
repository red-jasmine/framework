<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property string $resource_type
 * @property string $resource_id
 * @property string $interaction_type
 * @property string $interaction_time
 * @property int $quantity
 * @property string $user_type
 * @property string $user_id
 * @property string $user_nickname
 * @property string $user_avatar
 *
 */
class InteractionRecord extends Model
{

    public $incrementing = false;

    use HasOwner;

    protected $ownerColumn = 'user';

    use HasSnowflakeId;

    use SoftDeletes;


    public function getExtras() : array
    {
        return [];
    }

    public function setExtras(array $extras = []) : void
    {

    }

}
