<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $target_type
 * @property $target_id
 * @property $content
 * @property $root_id
 * @property $parent_id
 *
 */
class InteractionComment extends Model
{
    use SoftDeletes;
}
