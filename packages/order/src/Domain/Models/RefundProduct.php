<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundProduct extends Model
{

    public $incrementing = false;


    public function refund() : BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'id');
    }
}