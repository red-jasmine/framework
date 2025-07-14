<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property UserInterface $user
 * @property UserInterface $owner
 * @property ?UserInterface $cost_bearer
 * @property string $user_id
 * @property string $coupon_no
 * @property Money discount_amount
 */
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
        'used_at',
        'cost_bearer_type',
        'cost_bearer_id',
    ];

    protected function casts() : array
    {
        return [
            'coupon_id'       => 'integer',
            'used_at'         => 'datetime',
            'user'            => UserInterfaceCast::class,
            'cost_bearer'     => UserInterfaceCast::class,
            'discount_amount' => MoneyCast::class,

        ];
    }

    protected static function boot() : void
    {
        parent::boot();

        static::creating(function (CouponUsage $usage) {
            $usage->setUniqueIds();
            $usage->used_at = Carbon::now();
        });
    }

    public function newInstance($attributes = [], $exists = false) : static
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
    public function coupon() : BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
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