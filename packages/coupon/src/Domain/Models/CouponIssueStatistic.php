<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class CouponIssueStatistic extends Model implements OperatorInterface, OwnerInterface
{
    use HasOwner;
    use HasOperator;

    public $incrementing = false;

    protected $table = 'coupon_issue_statistics';

    protected $fillable = [
        'coupon_id',
        'date',
        'owner_type',
        'owner_id',
        'total_issued',
        'total_expired',
        'total_used',
        'total_cost',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'coupon_id' => 'integer',
            'date' => 'date',
            'total_issued' => 'integer',
            'total_expired' => 'integer',
            'total_used' => 'integer',
            'total_cost' => 'decimal:2',
            'last_updated' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CouponIssueStatistic $stat) {
            $stat->last_updated = Carbon::now();
        });

        static::updating(function (CouponIssueStatistic $stat) {
            $stat->last_updated = Carbon::now();
        });
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->total_issued = 0;
            $instance->total_expired = 0;
            $instance->total_used = 0;
            $instance->total_cost = 0;
            $instance->last_updated = Carbon::now();
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
     * 增加发放数量
     */
    public function incrementIssued(int $count = 1): void
    {
        $this->increment('total_issued', $count);
    }

    /**
     * 增加使用数量
     */
    public function incrementUsed(int $count = 1, float $cost = 0): void
    {
        $this->increment('total_used', $count);
        $this->increment('total_cost', $cost);
    }

    /**
     * 增加过期数量
     */
    public function incrementExpired(int $count = 1): void
    {
        $this->increment('total_expired', $count);
    }

    /**
     * 计算使用率
     */
    public function getUsageRate(): float
    {
        if ($this->total_issued === 0) {
            return 0;
        }

        return $this->total_used / $this->total_issued;
    }

    /**
     * 计算过期率
     */
    public function getExpiredRate(): float
    {
        if ($this->total_issued === 0) {
            return 0;
        }

        return $this->total_expired / $this->total_issued;
    }

    /**
     * 获取可用数量
     */
    public function getAvailableCount(): int
    {
        return $this->total_issued - $this->total_used - $this->total_expired;
    }

    /**
     * 计算平均成本
     */
    public function getAverageCost(): float
    {
        if ($this->total_used === 0) {
            return 0;
        }

        return $this->total_cost / $this->total_used;
    }

    /**
     * 获取统计摘要
     */
    public function getSummary(): array
    {
        return [
            'total_issued' => $this->total_issued,
            'total_used' => $this->total_used,
            'total_expired' => $this->total_expired,
            'available_count' => $this->getAvailableCount(),
            'usage_rate' => $this->getUsageRate(),
            'expired_rate' => $this->getExpiredRate(),
            'total_cost' => $this->total_cost,
            'average_cost' => $this->getAverageCost(),
            'last_updated' => $this->last_updated,
        ];
    }

    /**
     * 重置统计
     */
    public function reset(): void
    {
        $this->total_issued = 0;
        $this->total_expired = 0;
        $this->total_used = 0;
        $this->total_cost = 0;
        $this->last_updated = Carbon::now();
        $this->save();
    }

    /**
     * 作用域：按日期筛选
     */
    public function scopeByDate($query, Carbon $date)
    {
        return $query->where('date', $date->format('Y-m-d'));
    }

    /**
     * 作用域：按日期范围筛选
     */
    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
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