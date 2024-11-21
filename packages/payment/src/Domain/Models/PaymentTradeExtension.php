<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PaymentTradeExtension extends Model
{
    public $incrementing = false;


    public function trade() : BelongsTo
    {
        return $this->belongsTo(PaymentTrade::class, 'id', 'id');
    }
}
