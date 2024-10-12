<?php

namespace RedJasmine\Product\Domain\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Tag\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品标签
 */
class ProductTag extends Model implements OperatorInterface, OwnerInterface
{
    use SoftDeletes;

    use HasOwner;

    //use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-product.tables.prefix') . 'product_tags';
    }

    protected $casts = [
        'is_public' => 'boolean',
        'is_show'   => 'boolean',
        'status'    => TagStatusEnum::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'cluster',
        'icon',
        'color',
        'sort',
        'status',
        'is_show',
        'is_public',
    ];

}
