<?php

namespace RedJasmine\Community\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $id
 * @property $title
 * @property $content
 * @property $status
 * @property $is_top
 * @property $sort
 * @property $category_id
 * @property $approval_status
 * 是否精选
 *
 * @property $version
 *
 */
class CommunityTopic extends Model
{
    use SoftDeletes;
}
