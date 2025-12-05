<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 推广员 推广订单
 */
class PromoterOrder extends Model implements OperatorInterface
{

    public $incrementing = false;


    use HasSnowflakeId;

    use HasOperator;


    public function promoter() : BelongsTo
    {
        return $this->belongsTo(Promoter::class, 'id', 'promoter_id');
    }
}