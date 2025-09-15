<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property Money $price
 * @property ?Money $market_price
 * @property ?Money $cost_price
 */
class ProductSku extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasOwner;

    public $incrementing = false;
    protected $appends = ['price', 'market_price', 'cost_price'];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function setDeleted() : void
    {
        $this->deleted_at = $this->deleted_at ?? now();
        $this->status     = ProductStatusEnum::DELETED;
    }

    public function setOnSale() : void
    {
        $this->deleted_at = null;
        $this->status     = ProductStatusEnum::ON_SALE;
    }

    protected function casts() : array
    {
        return [
            'status'        => ProductStatusEnum::class,// 状态
            'modified_time' => 'datetime',
            'currency'      => CurrencyCast::class,
            'price'         => MoneyCast::class.':currency,price,1',
            'market_price'  => MoneyCast::class.':currency,market_price,1',
            'cost_price'    => MoneyCast::class.':currency,cost_price,1',
        ];
    }

}
