<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class RefundExtension extends Model
{


    public $incrementing = false;
    use HasSnowflakeId;

    protected function casts() : array
    {
        return [
            'good_details'     => GoodDetailCollectionCast::class,
            'pass_back_params' => 'array',
            'device'           => 'array',
            'client'           => 'array',
        ];
    }

    public function getTable() : string
    {
        return 'payment_refunds_extension';
    }

    public function refund() : BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'id');
    }
}
