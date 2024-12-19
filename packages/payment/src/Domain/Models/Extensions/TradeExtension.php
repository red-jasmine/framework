<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\Trade;


class TradeExtension extends Model
{
    public $incrementing = false;


    protected $casts = [
        'good_details'     => GoodDetailCollectionCast::class,
        'pass_back_params' => 'array',
        'device'           => 'array',
        'client'           => 'array',
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
