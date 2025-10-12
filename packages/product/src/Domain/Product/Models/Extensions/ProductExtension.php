<?php

namespace RedJasmine\Product\Domain\Product\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductExtension extends Model
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    /**
     * @return string
     */
    public function getTable() : string
    {
        return 'products_extension';
    }



    protected $casts = [
        //'promise_services' => PromiseServicesCastTransformer::class,
        'after_sales_services'      => 'array',
        'basic_attrs'      => 'array',
        'sale_attrs'       => 'array',
        'customize_attrs'  => 'array',
        'images'           => 'array',
        'videos'           => 'array',
        'tools'            => 'array',
        'extra'          => 'array',
        'form'             => 'array',
    ];

}
