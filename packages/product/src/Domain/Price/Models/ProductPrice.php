<?php

namespace RedJasmine\Product\Domain\Price\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Money\Casts\MoneyCast;
use RedJasmine\Money\Data\Money;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品级别价格汇总模型
 *
 * @property int $id
 * @property int $product_id
 * @property string $market
 * @property string $store
 * @property string $user_level
 * @property string $currency
 * @property Money|null $price
 * @property Money|null $market_price
 * @property Money|null $cost_price
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProductPrice extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'market',
        'store',
        'user_level',
        'currency',
        'price',
        'market_price',
        'cost_price',
    ];

    /**
     * 关联商品
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * 关联变体价格（同维度）
     */
    public function variantPrices() : HasMany
    {
        return $this->hasMany(ProductVariantPrice::class, 'product_id', 'product_id')
                    ->where(function ($query) {
                        $query->where('market', $this->market);
                    })
                    ->where(function ($query) {
                        $query->where('store', $this->store);
                    })
                    ->where(function ($query) {
                        $query->where('user_level', $this->user_level);
                    })
                    ->where(function ($query) {
                        $query->where('quantity', $this->quantity);
                    });
    }

    /**
     * 查询作用域：按维度查询
     */
    public function scopeByDimensions($query, string $market, string $store, string $userLevel, int $quantity = 1)
    {
        return $query->where(function ($q) use ($market) {
            $q->where('market', $market);
        })->where(function ($q) use ($store) {
            $q->where('store', $store);
        })->where(function ($q) use ($userLevel) {
            $q->where('user_level', $userLevel);
        })->where(function ($q) use ($quantity) {
            $q->where('quantity', $quantity);
        });
    }

    protected function casts() : array
    {
        return [
            'currency'     => CurrencyCast::class,
            'price'        => MoneyCast::class.':currency,price,1',
            'market_price' => MoneyCast::class.':currency,market_price,1',
            'cost_price'   => MoneyCast::class.':currency,cost_price,1',
        ];
    }
}

