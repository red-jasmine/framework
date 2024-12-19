<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class RefundExtension extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;


    protected $casts = [
        'good_details'     => GoodDetailCollectionCast::class,
        'pass_back_params' => 'array',
        'device'           => 'array',
        'client'           => 'array',
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_refunds_extensions';
    }

    public function refund() : BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'id');
    }
}
