<?php

namespace RedJasmine\ShoppingCart\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use Carbon\Carbon;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;

/**
 * 购物车商品项实体
 *
 * @property int $id
 * @property int $cart_id
 * @property CartProductIdentity $identity
 * @property int $quantity
 * @property float $price
 * @property float $original_price
 * @property float $discount_amount
 * @property float $subtotal
 * @property bool $selected
 * @property array $properties
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ShoppingCart $cart
 */
class ShoppingCartProduct extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;

    protected $casts = [
        'price'           => 'decimal:2',
        'original_price'  => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal'        => 'decimal:2',
        'selected'        => 'boolean',
        'properties'      => 'array',
        'identity'        => CartProductIdentity::class,
    ];

    /**
     * 模型生命周期钩子
     */
    protected static function boot() : void
    {
        parent::boot();

        // 保存时计算小计
        static::saving(function (ShoppingCartProduct $product) {
            $product->calculateSubtotal();
        });
    }

    /**
     * 关联关系定义
     */
    public function cart() : BelongsTo
    {
        return $this->belongsTo(ShoppingCart::class, 'cart_id', 'id');
    }

    /**
     * 业务方法
     */
    public function calculateSubtotal() : void
    {
        $this->subtotal = ($this->price - $this->discount_amount) * $this->quantity;
    }

    public function updateQuantity(int $quantity) : void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('商品数量必须大于0');
        }

        $this->quantity = $quantity;
        $this->calculateSubtotal();
    }

    public function isAvailable() : bool
    {
        // 这里应该调用库存服务检查库存
        // 使用 $this->identity 调用 StockServiceInterface::checkStock($identity, $this->quantity)
        // 暂时返回true，实际实现时需要集成库存服务
        return true;
    }

    public function getFinalPrice() : float
    {
        return $this->price - $this->discount_amount;
    }

    public function getDiscountRate() : float
    {
        if ($this->original_price <= 0) {
            return 0;
        }
        return round(($this->discount_amount / $this->original_price) * 100, 2);
    }

    public function getPropertiesText() : string
    {
        if (empty($this->properties)) {
            return '';
        }

        $texts = [];
        foreach ($this->properties as $property) {
            if (isset($property['name']) && isset($property['value'])) {
                $texts[] = $property['name'].':'.$property['value'];
            }
        }

        return implode(';', $texts);
    }
} 