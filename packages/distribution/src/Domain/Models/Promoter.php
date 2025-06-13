<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 分销员
 * @property bool $isPromoter 是否推广员
 * @property int $level 推广等级
 * @property int $parentId 推广上级
 */
class Promoter extends Model implements OperatorInterface, OwnerInterface
{

    public $incrementing = false;


    use HasSnowflakeId;

    use HasOperator;


    use HasOwner;

    protected function casts() : array
    {
        return [
            'status' => PromoterStatusEnum::class,
        ];
    }


    public function parent() : BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }


    public function group() : BelongsTo
    {
        return $this->belongsTo(PromoterGroup::class, 'group_id', 'id');
    }


    public function team() : BelongsTo
    {
        return $this->belongsTo(PromoterTeam::class, 'team_id', 'id');
    }


    public function users() : HasMany
    {
        return $this->hasMany(PromoterBindUser::class, 'promoter_id', 'id');
    }

}
