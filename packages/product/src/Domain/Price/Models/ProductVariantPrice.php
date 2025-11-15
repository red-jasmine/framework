<?php

namespace RedJasmine\Product\Domain\Price\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Money\Casts\MoneyCast;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductVariant;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品变体价格模型
 * 
 * @property int $id
 * @property int $product_id
 * @property int $variant_id
 * @property string $market
 * @property string $store
 * @property string $user_level
 * @property string $currency
 * @property \RedJasmine\Money\Data\Money $price
 * @property \RedJasmine\Money\Data\Money|null $market_price
 * @property \RedJasmine\Money\Data\Money|null $cost_price
 * @property array|null $quantity_tiers
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ProductVariantPrice extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'product_variant_prices';

    protected $fillable = [
        'product_id',
        'variant_id',
        'market',
        'store',
        'user_level',
        'currency',
        'price',
        'market_price',
        'cost_price',
        'quantity_tiers',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'currency' => CurrencyCast::class,
            'price' => MoneyCast::class . ':currency,price,1',
            'market_price' => MoneyCast::class . ':currency,market_price,1',
            'cost_price' => MoneyCast::class . ':currency,cost_price,1',
            'quantity_tiers' => 'array',
            'priority' => 'integer',
        ];
    }

    /**
     * 关联商品
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * 关联SKU
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    /**
     * 查询作用域：按维度查询
     */
    public function scopeByDimensions($query, string $market, string $store, string $userLevel)
    {
        return $query->where(function ($q) use ($market) {
            $q->where('market', $market)
                ->orWhere('market', '*');
        })->where(function ($q) use ($store) {
            $q->where('store', $store)
                ->orWhere('store', '*');
        })->where(function ($q) use ($userLevel) {
            $q->where('user_level', $userLevel)
                ->orWhere('user_level', '*');
        });
    }
}

