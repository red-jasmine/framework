<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;


class PaymentTradeExtension extends Model
{
    public $incrementing = false;


    protected $casts = [
        'detail'           => 'array',
        'detail'           => GoodDetailCollectionCast::class,
        'pass_back_params' => 'array',
        'expands'          => 'array',
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_trade_extensions';
    }

    public function trade() : BelongsTo
    {
        return $this->belongsTo(PaymentTrade::class, 'id', 'id');
    }
}
