<?php

namespace RedJasmine\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Traits\HasDateTimeFormatter;

class PaymentInfo extends Model
{
    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $casts = [
        'extends'          => 'array',
        'pass_back_params' => 'array'
    ];

    public function payment() : BelongsTo
    {
        return $this->belongsTo(Payment::class, 'id', 'id');
    }
}
