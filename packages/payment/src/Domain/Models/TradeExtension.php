<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;


class TradeExtension extends Model
{
    public $incrementing = false;


    protected $casts = [
        'good_details'     => GoodDetailCollectionCast::class,
        'pass_back_params' => 'array',
        'expands'          => 'array',
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades_extensions';
    }

    public function trade() : BelongsTo
    {
        return $this->belongsTo(Trade::class, 'id', 'id');
    }
}
