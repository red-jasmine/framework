<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 推广员
 * @property bool $isPromoter 是否推广员
 * @property int $level 推广等级
 * @property int $parentId 推广上级
 */
class Promoter extends Model
{
}
