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

    protected $table = 'order_products_extension';

    protected $casts = [
        'after_sales_services' => 'array',
        'customized'                 => 'array',
        'buyer_extra'          => 'array',
        'seller_extra'         => 'array',
        'other_extra'          => 'array',
        'tools'                => 'array',
    ];


}
