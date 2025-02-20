<?php

namespace RedJasmine\Order\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderProductExtension extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;


    public $incrementing = false;

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix','jasmine_') . 'order_products_extension';
    }

    protected $casts = [
        'after_sales_services' => 'array',
        'form'                 => 'array',
        'buyer_extras'        => 'array',
        'seller_extras'       => 'array',
        'other_extras'        => 'array',
        'tools'                => 'array',
    ];


}
