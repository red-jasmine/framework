<?php

namespace RedJasmine\Order\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderRefundExtension extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    public $incrementing = false;

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix','jasmine_') . 'order_refunds_extension';
    }

    protected $fillable = [];


    protected $casts = [
        'images' => 'array'
    ];

}
