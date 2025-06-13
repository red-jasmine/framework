<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;

/**
 * 分销员
 * @property bool $isPromoter 是否推广员
 * @property int $level 推广等级
 * @property int $parentId 推广上级
 */
class Promoter extends Model
{

    protected function casts() : array
    {
        return [
            'status' => PromoterStatusEnum::class,
        ];
    }
}
