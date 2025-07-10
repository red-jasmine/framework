<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class UserCoupon extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    public $incrementing = false;

    protected $table = 'user_coupons';

    protected $fillable = [
        'coupon_id',
        'owner_type',
        'owner_id',
        'coupon_no',
        'user_type',
        'user_id',
        'status',
        'issue_time',
        'expire_time',
        'used_time',
        'order_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => UserCouponStatusEnum::class,
            'issue_time' => 'datetime',
            'expire_time' => 'datetime',
            'used_time' => 'datetime',
            'coupon_id' => 'integer',
            'order_id' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (UserCoupon $userCoupon) {
            $userCoupon->setUniqueIds();
            $userCoupon->status = UserCouponStatusEnum::AVAILABLE;
            $userCoupon->issue_time = Carbon::now();
        });
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->status = UserCouponStatusEnum::AVAILABLE;
            $instance->issue_time = Carbon::now();
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
     * 检查是否可用
     */
    public function isAvailable(): bool
    {
        return $this->status === UserCouponStatusEnum::AVAILABLE && !$this->isExpired();
    }

    /**
     * 检查是否已过期
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expire_time);
    }

    /**
     * 检查是否已使用
     */
    public function isUsed(): bool
    {
        return $this->status === UserCouponStatusEnum::USED;
    }

    /**
     * 使用优惠券
     */
    public function use(int $orderId): void
    {
        if (!$this->isAvailable()) {
            throw new CouponException('优惠券不可用');
        }

        $this->status = UserCouponStatusEnum::USED;
        $this->used_time = Carbon::now();
        $this->order_id = $orderId;
        $this->save();
    }

    /**
     * 过期优惠券
     */
    public function expire(): void
    {
        if ($this->status !== UserCouponStatusEnum::AVAILABLE) {
            return;
        }

        $this->status = UserCouponStatusEnum::EXPIRED;
        $this->save();
    }

    /**
     * 获取剩余天数
     */
    public function getRemainingDays(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expire_time);
    }

    /**
     * 获取剩余小时数
     */
    public function getRemainingHours(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInHours($this->expire_time);
    }

    /**
     * 获取状态文本
     */
    public function getStatusText(): string
    {
        if ($this->isExpired()) {
            return '已过期';
        }

        return match ($this->status) {
            UserCouponStatusEnum::AVAILABLE => '可用',
            UserCouponStatusEnum::USED => '已使用',
            UserCouponStatusEnum::EXPIRED => '已过期',
        };
    }

    /**
     * 获取显示名称
     */
    public function getDisplayName(): string
    {
        return $this->coupon->name . ' - ' . $this->getStatusText();
    }

    /**
     * 作用域：可用的优惠券
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', UserCouponStatusEnum::AVAILABLE)
                    ->where('expire_time', '>', Carbon::now());
    }

    /**
     * 作用域：已过期的优惠券
     */
    public function scopeExpired($query)
    {
        return $query->where('status', UserCouponStatusEnum::EXPIRED)
                    ->orWhere('expire_time', '<=', Carbon::now());
    }

    /**
     * 作用域：已使用的优惠券
     */
    public function scopeUsed($query)
    {
        return $query->where('status', UserCouponStatusEnum::USED);
    }

    /**
     * 作用域：按用户筛选
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
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