<?php

namespace RedJasmine\ShoppingCart\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use InvalidArgumentException;

/**
 * 购物车商品项实体
 *
 */
class ShoppingCartProduct extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;

    protected $casts    = [
        'price' => MoneyCast::class,

    ];
    protected $fillable = [
        'cart_id'
    ];

    /**
     * 模型生命周期钩子
     */
    protected static function boot() : void
    {
        parent::boot();

        // 保存时计算小计
        static::saving(function (ShoppingCartProduct $product) {

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
            throw new InvalidArgumentException('商品数量必须大于0');
        }

        $this->quantity = $quantity;

    }

    public function isAvailable() : bool
    {
        // 这里应该调用库存服务检查库存
        // 使用 $this->identity 调用 StockServiceInterface::checkStock($product, $this->quantity)
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


    public function setProduct(ProductIdentity $cartProduct) : void
    {
        $this->seller_type  = $cartProduct->seller->getType();
        $this->seller_id    = $cartProduct->seller->getID();
        $this->product_type = $cartProduct->productType;
        $this->product_id   = $cartProduct->productId;
        $this->sku_id       = $cartProduct->skuId;
    }

    public function getProduct() : ProductIdentity
    {
        return ProductIdentity::from([
            'seller_type'  => $this->seller_type,
            'seller_id'    => $this->seller_id,
            'product_type' => $this->product_type,
            'product_id'   => $this->product_id,
            'sku_id'       => $this->sku_id,
        ]);
    }
} 