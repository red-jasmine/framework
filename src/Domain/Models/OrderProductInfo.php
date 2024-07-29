<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderProductInfo extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;


    public $incrementing = false;


    protected $casts = [
        'buyer_expands'  => 'array',
        'seller_expands' => 'array',
        'other_expands'  => 'array',
        'tools'          => 'array',
    ];


}
