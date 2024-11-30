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
    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix','jasmine_') . 'order_infos';
    }
    protected $casts = [
        'buyer_expands'       => 'array',
        'seller_expands'      => 'array',
        'other_expands'       => 'array',
        'form'                => 'array',
        'tools'               => 'array',
    ];
}
