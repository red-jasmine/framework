<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;


class ProductSku extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasOwner;

    public $incrementing = false;


    protected $casts = [
        'status' => ProductStatusEnum::class,
    ];


    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    /**
     * 允许销售
     *
     * @return bool
     * @throws StockException
     */
    public function isAllowSale() : bool
    {
        if (!in_array($this->status, [ ProductStatusEnum::ON_SALE, ProductStatusEnum::PRE_SALE ], true)) {
            throw  StockException::newFromCodes(StockException::SKU_FORBID_SALE);
        }
        return true;
    }
}
