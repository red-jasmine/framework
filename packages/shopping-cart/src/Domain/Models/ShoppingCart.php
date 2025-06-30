<?php

namespace RedJasmine\ShoppingCart\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\ShoppingCart\Domain\Models\Enums\ShoppingCartStatusEnum;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\ShoppingCart\Exceptions\ShoppingCartException;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use Carbon\Carbon;

/**
 * 购物车聚合根
 * 
 * @property int $id
 * @property string $market
 * @property UserInterface $owner
 * @property UserInterface $operator
 * @property ShoppingCartStatusEnum $status
 * @property float $total_amount
 * @property float $discount_amount
 * @property float $final_amount
 * @property Carbon $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|ShoppingCartProduct[] $products
 */
class ShoppingCart extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    public $incrementing = false;

    /**
     * 类型转换配置
     */
    protected function casts(): array
    {
        return [
            'status' => ShoppingCartStatusEnum::class,
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'expired_at' => 'datetime',
        ];
    }

    /**
     * 模型生命周期钩子
     */
    protected static function boot(): void
    {
        parent::boot();

        // 创建时设置过期时间
        static::creating(function (ShoppingCart $cart) {
            if (!$cart->expired_at) {
                $cart->expired_at = Carbon::now()->addDays(30);
            }
            if (!$cart->status) {
                $cart->status = ShoppingCartStatusEnum::ACTIVE;
            }
        });

        // 保存时重新计算金额
        static::saving(function (ShoppingCart $cart) {
            if ($cart->relationLoaded('products')) {
                $cart->calculateAmount();
            }
        });
    }

    /**
     * 新实例初始化
     */
    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('products', Collection::make());
        }

        return $instance;
    }

    /**
     * 关联关系定义
     */
    public function products(): HasMany
    {
        return $this->hasMany(ShoppingCartProduct::class, 'cart_id', 'id');
    }

    /**
     * 查询作用域
     */
    public function scopeActive($query)
    {
        return $query->where('status', ShoppingCartStatusEnum::ACTIVE);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', ShoppingCartStatusEnum::EXPIRED);
    }

    /**
     * 业务方法
     */
    public function addProduct(ShoppingCartProduct $product): void
    {
        if ($this->status !== ShoppingCartStatusEnum::ACTIVE) {
            throw new ShoppingCartException('购物车状态不允许添加商品');
        }

        $existingProduct = $this->products()->where([
            'shop_type' => $product->identity->shopType,
            'shop_id' => $product->identity->shopId,
            'product_type' => $product->identity->productType,
            'product_id' => $product->identity->productId,
            'sku_id' => $product->identity->skuId,
        ])->first();

        if ($existingProduct) {
            $existingProduct->updateQuantity($existingProduct->quantity + $product->quantity);
        } else {
            $product->cart_id = $this->id;

        }
        $this->load('products');
        $this->calculateAmount();
    }

    public function removeProduct(CartProductIdentity $identity): void
    {
        $product = $this->products()->where([
            'shop_type' => $identity->shopType,
            'shop_id' => $identity->shopId,
            'product_type' => $identity->productType,
            'product_id' => $identity->productId,
            'sku_id' => $identity->skuId,
        ])->first();
        if ($product) {
            $product->delete();
        }
        $this->load('products');
        $this->calculateAmount();
    }

    public function updateQuantity(CartProductIdentity $identity, int $quantity): void
    {
        $product = $this->products()->where([
            'shop_type' => $identity->shopType,
            'shop_id' => $identity->shopId,
            'product_type' => $identity->productType,
            'product_id' => $identity->productId,
            'sku_id' => $identity->skuId,
        ])->first();
        if (!$product) {
            throw new ShoppingCartException('购物车商品不存在');
        }
        $product->updateQuantity($quantity);

        $this->load('products');
        $this->calculateAmount();
    }

    public function selectProduct(CartProductIdentity $identity, bool $selected): void
    {
        $product = $this->products()->where([
            'shop_type' => $identity->shopType,
            'shop_id' => $identity->shopId,
            'product_type' => $identity->productType,
            'product_id' => $identity->productId,
            'sku_id' => $identity->skuId,
        ])->first();
        if (!$product) {
            throw new ShoppingCartException('购物车商品不存在');
        }
        $product->selected = $selected;
        $product->save();
        $this->load('products');
        $this->calculateAmount();
    }

    public function calculateAmount(): void
    {
        $totalAmount = 0;
        $discountAmount = 0;

        foreach ($this->products as $product) {
            if ($product->selected) {
                $totalAmount += $product->original_price * $product->quantity;
                $discountAmount += $product->discount_amount * $product->quantity;
            }
        }

        $this->total_amount = $totalAmount;
        $this->discount_amount = $discountAmount;
        $this->final_amount = $totalAmount - $discountAmount;
    }

    public function validateStock(): bool
    {
        // 这里应该调用库存服务检查所有选中商品的库存
        // 使用 $product->identity 调用 StockServiceInterface::checkStock($identity, $product->quantity)
        foreach ($this->products as $product) {
            if ($product->selected && !$product->isAvailable()) {
                return false;
            }
        }
        return true;
    }

    public function clear(): void
    {
        $this->products = Collection::make();
        $this->status = ShoppingCartStatusEnum::CLEARED;
        $this->calculateAmount();
    }

    public function isExpired(): bool
    {
        return $this->expired_at->isPast() || $this->status === ShoppingCartStatusEnum::EXPIRED;
    }

    public function getSelectedProducts(): Collection
    {
        return $this->products->filter(function ($product) {
            return $product->selected;
        });
    }

    public function getProductCount(): int
    {
        return $this->products->sum('quantity');
    }

    public function getSelectedProductCount(): int
    {
        return $this->getSelectedProducts()->sum('quantity');
    }
} 