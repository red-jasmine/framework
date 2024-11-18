<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderRefundInfo extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix') . 'order_refund_infos';
    }

    protected $fillable = [];


    protected $casts = [
        'images' => 'array'
    ];

}
