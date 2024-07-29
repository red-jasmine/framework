<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderInfo extends Model
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;

    protected $casts = [
        'buyer_extends'  => 'array',
        'seller_extends' => 'array',
        'other_extends'  => 'array',
        'tools'          => 'array',
    ];
}
