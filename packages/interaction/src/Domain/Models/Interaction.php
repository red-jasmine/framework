<?php

namespace RedJasmine\Interaction\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

/**
 * 互动数据
 * @property-read int $id
 * @property-read int $like_count
 * @property-read int $favorite_count
 * @property-read int $comment_count
 * @property-read int $view_count
 * @property-read int $share_count
 */
class Interaction extends Model
{

    use SoftDeletes;
}
