<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServicesCastTransformer;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductInfo extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    /**
     * @return string
     */
//    public function getTable()
//    {
//        return config('red-jasmine-product.tables.prefix'). 'product_infos';
//    }

    protected $casts = [
        'promise_services' => PromiseServicesCastTransformer::class,
        'basic_props'      => 'array',
        'sale_props'       => 'array',
        'customize_props'  => 'array',
        'images'           => 'array',
        'videos'           => 'array',
        'tools'            => 'array',
        'expands'          => 'array',
        'form'             => 'array',
    ];

}
