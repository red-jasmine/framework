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
        return config('red-jasmine-order.tables.prefix','jasmine_') . 'order_product_extensions';
    }

    protected $casts = [
        'after_sales_services' => 'array',
        'form'                 => 'array',
        'buyer_expands'        => 'array',
        'seller_expands'       => 'array',
        'other_expands'        => 'array',
        'tools'                => 'array',
    ];


}
