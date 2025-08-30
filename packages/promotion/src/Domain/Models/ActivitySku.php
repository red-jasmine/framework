<?php

namespace RedJasmine\Promotion\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Promotion\Domain\Models\Enums\SkuStatusEnum;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ActivitySku extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'promotion_activity_skus';

    protected $fillable = [
        'activity_id',
        'product_id',
        'sku_id',
        'activity_product_id',
        'properties_name',
        'image',
        'original_price',
        'activity_price',
        'discount_rate',
        'activity_stock',
        'locked_stock',
        'user_purchase_limit',
        'status',
        'is_show',
        'activity_sales_volume',
        'activity_sales_amount',
    ];

    /**
     * 关联活动
     */
    public function activity() : BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * 关联活动商品
     */
    public function activityProduct() : BelongsTo
    {
        return $this->belongsTo(ActivityProduct::class);
    }

    /**
     * 检查SKU是否可用
     */
    public function isAvailable() : bool
    {
        return $this->status === SkuStatusEnum::ACTIVE && $this->is_show;
    }

    /**
     * 锁定库存
     */
    public function lockStock(int $quantity) : bool
    {
        if (!$this->hasAvailableStock($quantity)) {
            return false;
        }

        $this->locked_stock += $quantity;
        return $this->save();
    }

    /**
     * 检查是否有可用库存
     */
    public function hasAvailableStock(int $quantity = 1) : bool
    {
        if ($this->activity_stock === null) {
            return true; // 无限库存
        }

        return ($this->activity_stock - $this->locked_stock) >= $quantity;
    }

    /**
     * 释放库存
     */
    public function releaseStock(int $quantity) : bool
    {
        $this->locked_stock = max(0, $this->locked_stock - $quantity);
        return $this->save();
    }

    /**
     * 消费库存
     */
    public function consumeStock(int $quantity) : bool
    {
        if ($this->activity_stock !== null) {
            $this->activity_stock -= $quantity;
        }
        $this->locked_stock          = max(0, $this->locked_stock - $quantity);
        $this->activity_sales_volume += $quantity;

        return $this->save();
    }

    protected function casts() : array
    {
        return [

            'original_price' => MoneyCast::class,
            'activity_price' => MoneyCast::class,
            'status'         => SkuStatusEnum::class,
            'is_show'        => 'boolean',

        ];
    }
}