<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Coupon\Domain\Models\Enums\CostBearerTypeEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class CouponUsage extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    public $incrementing = false;

    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id',
        'user_coupon_id',
        'user_id',
        'order_id',
        'threshold_amount',
        'original_amount',
        'discount_amount',
        'final_amount',
        'used_at',
        'cost_bearer_type',
        'cost_bearer_id',
        'cost_bearer_name',
    ];

    protected function casts(): array
    {
        return [
            'coupon_id' => 'integer',
            'user_coupon_id' => 'integer',
            'user_id' => 'integer',
            'order_id' => 'integer',
            'threshold_amount' => 'decimal:2',
            'original_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'used_at' => 'datetime',
            'cost_bearer_type' => CostBearerTypeEnum::class,
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CouponUsage $usage) {
            $usage->setUniqueIds();
            $usage->used_at = Carbon::now();
        });
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->used_at = Carbon::now();
        }

        return $instance;
    }

    /**
     * 优惠券关联
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * 用户优惠券关联
     */
    public function userCoupon(): BelongsTo
    {
        return $this->belongsTo(UserCoupon::class, 'user_coupon_id');
    }

    /**
     * 计算优惠比例
     */
    public function getDiscountRatio(): float
    {
        if ($this->original_amount <= 0) {
            return 0;
        }

        return $this->discount_amount / $this->original_amount;
    }

    /**
     * 计算节省金额
     */
    public function getSavedAmount(): float
    {
        return $this->discount_amount;
    }

    /**
     * 获取成本承担方信息
     */
    public function getCostBearerInfo(): array
    {
        return [
            'type' => $this->cost_bearer_type->value,
            'id' => $this->cost_bearer_id,
            'name' => $this->cost_bearer_name,
        ];
    }

    /**
     * 获取使用摘要
     */
    public function getUsageSummary(): string
    {
        return "订单{$this->order_id}使用优惠券节省{$this->discount_amount}元";
    }

    /**
     * 作用域：按优惠券筛选
     */
    public function scopeByCoupon($query, int $couponId)
    {
        return $query->where('coupon_id', $couponId);
    }

    /**
     * 作用域：按用户筛选
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * 作用域：按订单筛选
     */
    public function scopeByOrder($query, int $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * 作用域：按成本承担方筛选
     */
    public function scopeByCostBearer($query, CostBearerTypeEnum $type, string $id)
    {
        return $query->where('cost_bearer_type', $type)
                    ->where('cost_bearer_id', $id);
    }

    /**
     * 作用域：按时间范围筛选
     */
    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('used_at', [$startDate, $endDate]);
    }
} 