<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class CouponUsage extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    public $incrementing = false;

    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id',
        'owner_type',
        'owner_id',
        'coupon_no',
        'user_type',
        'user_id',
        'order_no',
        'threshold_amount',
        'discount_amount',
        'final_discount_amount',
        'used_at',
        'cost_bearer_type',
        'cost_bearer_id',
    ];

    protected function casts(): array
    {
        return [
            'coupon_id' => 'integer',
            'threshold_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_discount_amount' => 'decimal:2',
            'used_at' => 'datetime',
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
     * 计算优惠比例
     */
    public function getDiscountRatio(): float
    {
        if ($this->threshold_amount <= 0) {
            return 0;
        }

        return $this->discount_amount / $this->threshold_amount;
    }

    /**
     * 计算节省金额
     */
    public function getSavedAmount(): float
    {
        return $this->final_discount_amount;
    }

    /**
     * 获取成本承担方信息
     */
    public function getCostBearerInfo(): array
    {
        return [
            'type' => $this->cost_bearer_type,
            'id' => $this->cost_bearer_id,
        ];
    }

    /**
     * 获取使用摘要
     */
    public function getUsageSummary(): string
    {
        return "订单{$this->order_no}使用优惠券节省{$this->final_discount_amount}元";
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
    public function scopeByOrder($query, string $orderNo)
    {
        return $query->where('order_no', $orderNo);
    }

    /**
     * 作用域：按时间范围筛选
     */
    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('used_at', [$startDate, $endDate]);
    }

    /**
     * 作用域：按所有者筛选
     */
    public function scopeByOwner($query, string $ownerType, string $ownerId)
    {
        return $query->where('owner_type', $ownerType)
                    ->where('owner_id', $ownerId);
    }
} 