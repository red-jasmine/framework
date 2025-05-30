<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Casts\GoodDetailCollectionCast;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class TradeExtension extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;


    protected function casts() : array
    {
        return
            [
                'good_details'     => GoodDetailCollectionCast::class,
                'pass_back_params' => 'array',
                'device'           => 'array',
                'client'           => 'array',
            ];
    }

    public function getTable() : string
    {
        return 'payment_trades_extension';
    }

    public function trade() : BelongsTo
    {
        return $this->belongsTo(Trade::class, 'trade_id', 'id');
    }
}
