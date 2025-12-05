<?php

namespace RedJasmine\Promotion\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityProductStatusEnum;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ActivityProduct extends Model implements OwnerInterface, OperatorInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'promotion_activity_products';

    protected $fillable = [
        'activity_id',
        'product_id',
        'owner_type',
        'owner_id',
        'seller_type',
        'seller_id',
        'title',
        'image',
        'original_price',
        'sales',
        'activity_price',
        'discount_rate',
        'activity_stock',
        'locked_stock',
        'user_purchase_limit',
        'sku_participation_mode',
        'price_setting_mode',
        'stock_management_mode',
        'start_time',
        'end_time',
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
     * 关联SKU
     */
    public function skus() : HasMany
    {
        return $this->hasMany(ActivitySku::class);
    }

    /**
     * 检查商品是否可以参与活动
     */
    public function canParticipate() : bool
    {
        return $this->status === ActivityProductStatusEnum::ACTIVE
               && $this->is_show
               && ($this->start_time === null || $this->start_time <= now())
               && ($this->end_time === null || $this->end_time >= now());
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

            'start_time' => 'datetime',
            'end_time'   => 'datetime',
            'status'     => ActivityProductStatusEnum::class,
            'is_show'    => 'boolean',
        ];
    }
}