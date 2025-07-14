<?php

namespace RedJasmine\Coupon\Domain\Models;


use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTargetEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Generator\CouponNoGenerator;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property UserCouponStatusEnum $status
 * @property UserInterface $user
 * @property UserInterface $owner
 * @property string $user_id
 * @property string $coupon_no
 */
class UserCoupon extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;

    public    $incrementing = false;
    protected $fillable     = [
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
        'owner',
        'user',
    ];

    protected static function boot() : void
    {
        parent::boot();

        static::creating(function (UserCoupon $userCoupon) {
            $userCoupon->setUniqueIds();
            $userCoupon->status     = UserCouponStatusEnum::AVAILABLE;
            $userCoupon->issue_time = Carbon::now();
        });
    }

    public function scopeOnlyUser(Builder $query, UserInterface $user) : Builder
    {
        return $query->where(
            [
                'user_id'   => $user->getID(),
                'user_type' => $user->getType(),
            ]
        );
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->status     = UserCouponStatusEnum::AVAILABLE;
            $instance->issue_time = Carbon::now();
        }
        if (!$instance->exists && !empty($attributes)) {
            $instance->generateNo();
        }

        return $instance;
    }

    protected function generateNo() : void
    {

        if (!$this->coupon_no) {
            $this->coupon_no = new CouponNoGenerator()->generator($this);
        }

    }

    /**
     * 优惠券关联
     */
    public function coupon() : BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * 检查是否已使用
     */
    public function isUsed() : bool
    {
        return $this->status === UserCouponStatusEnum::USED;
    }

    /**
     * 使用优惠券
     * @throws CouponException
     */
    public function use() : void
    {
        if (!$this->isAvailable()) {
            throw new CouponException('优惠券不可用');
        }

        $this->status    = UserCouponStatusEnum::USED;
        $this->used_time = Carbon::now();


        $this->coupon->use();

    }

    /**
     * 检查是否可用
     */
    public function isAvailable() : bool
    {
        return $this->status === UserCouponStatusEnum::AVAILABLE && !$this->isExpired();
    }

    /**
     * 检查是否已过期
     */
    public function isExpired() : bool
    {
        return Carbon::now()->isAfter($this->validity_end_time);
    }

    /**
     * 过期优惠券
     */
    public function expire() : void
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
    public function getRemainingDays() : int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expire_time);
    }

    /**
     * 获取剩余小时数
     */
    public function getRemainingHours() : int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInHours($this->expire_time);
    }

    /**
     * 获取显示名称
     */
    public function getDisplayName() : string
    {
        return $this->coupon->name.' - '.$this->getStatusText();
    }

    /**
     * 获取状态文本
     */
    public function getStatusText() : string
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
     * 作用域：可用的优惠券
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', UserCouponStatusEnum::AVAILABLE)
                     ->where('validity_start_time', '<=', Carbon::now())
                     ->where('validity_end_time', '>', Carbon::now());
    }

    /**
     * 作用域：已过期的优惠券
     */
    public function scopeExpired($query)
    {
        return $query->where('status', UserCouponStatusEnum::EXPIRED)
                     ->orWhere('validity_end_time', '<=', Carbon::now());
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

    /**
     * 是否可以使用
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return bool
     */
    public function canUse(ProductPurchaseFactor $productPurchaseFactor) : bool
    {
        // 验证门槛
        if (!$this->isReachedThreshold(
            $productPurchaseFactor->getProductInfo()->getProductAmountInfo()->totalPrice, $productPurchaseFactor->quantity)) // TODO 验证 使用规则
        {
            return false;
        }
        return true;
    }

    /**
     * 是否可以达到门槛值
     *
     * @param  Money  $money
     * @param  int  $quantity
     *
     * @return bool
     */
    protected function isReachedThreshold(Money $money, int $quantity) : bool
    {
        if ($this->coupon->threshold_type === ThresholdTypeEnum::QUANTITY) {
            if ($quantity < $this->coupon->threshold_value) {
                return false;
            }
        }

        if ($this->coupon->threshold_type === ThresholdTypeEnum::AMOUNT) {
            $productAmountValue   = $money->getAmount();
            $thresholdAmountValue = bcmul($this->coupon->threshold_value, 100, 2);
            if (bccomp($productAmountValue, $thresholdAmountValue, 2) < 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return array{amount:Money,quantity:int}
     */
    public function getOrderMeetRulesAmountAndQuantity(OrderData $orderData) : array
    {
        $amount   = Money::parse(0, $orderData->getOrderAmountInfo()->productAmount->getCurrency());
        $quantity = 0;
        foreach ($orderData->products as $productPurchaseFactor) {
            /**
             * @var ProductPurchaseFactor $product
             */
            // TODO 符合使用规则验证  验证规则, 商品规则、品牌规则、分类规则、用户分组规则等
            $amount = $amount->add($productPurchaseFactor->getProductInfo()
                                                         ->getProductAmountInfo()
                                                         ->getProductAmount());

            $quantity = $quantity + $productPurchaseFactor->quantity;
        }

        return [
            'amount'   => $amount,
            'quantity' => $quantity,
        ];
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return array{amount:Money,quantity:int,is_can_use:bool}
     */
    public function canUseOrder(OrderData $orderData) : array
    {
        $result = $this->getOrderMeetRulesAmountAndQuantity($orderData);
        // 验证门槛
        // 判断订单商品是否符合规则，计算出符合规则的金额和数量
        $amount               = $result['amount'];
        $quantity             = $result['quantity'];
        $result['is_can_use'] = true;
        // 判断是否使用门槛
        if (!$this->isReachedThreshold($amount, $quantity)) {
            $result['is_can_use'] = false;
            return $result;
        }

        return $result;
    }

    // 获取优惠金额
    public function calculateDiscountAmount(Money $amount) : Money
    {

        if ($this->coupon->discount_amount_type === DiscountAmountTypeEnum::FIXED_AMOUNT) {
            return Money::parse($this->coupon->discount_amount_value, $amount->getCurrency());
        }
        if ($this->coupon->discount_amount_type === DiscountAmountTypeEnum::PERCENTAGE) {
            $discountAmount = $amount->multiply(bcsub(1, bcdiv($this->coupon->discount_amount_value, 100, 4), 4));

            // 如果设置最大优惠金额
            if (bccomp($this->coupon->max_discount_amount, 0, 2) > 0) {
                $maxDiscountAmount = Money::parse($this->coupon->max_discount_amount, $discountAmount->getCurrency());
                if ($maxDiscountAmount < $discountAmount) {
                    $discountAmount = $maxDiscountAmount;
                }
            }
            return $discountAmount;
        }
        return Money::parse(0, $amount->getCurrency());
    }

    protected function casts() : array
    {
        return [
            'status'              => UserCouponStatusEnum::class,
            'user'                => UserInterfaceCast::class,
            'discount_target'     => DiscountTargetEnum::class,
            'issue_time'          => 'datetime',
            'validity_start_time' => 'datetime',
            'validity_end_time'   => 'datetime',
            'used_time'           => 'datetime',
            'coupon_id'           => 'integer',
            'order_id'            => 'integer',
        ];
    }


    public function usages() : HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_no', 'coupon_no');
    }
}