<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\CouponTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Domain\Services\CouponRuleService;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Domain\Casts\TimeConfigCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property ?TimeConfigData $delayed_effective_time
 * @property ?TimeConfigData $validity_time
 * @property ?UserInterface $cost_bearer
 */
class Coupon extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;


    public $incrementing = false;

    protected $table = 'coupons';

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_show',
        'status',
        'discount_level',
        'discount_amount_type',
        'discount_amount_value',
        'threshold_type',
        'threshold_value',
        'max_discount_amount',
        'validity_type',
        'validity_start_time',
        'validity_end_time',
        'delayed_effective_time_type',
        'delayed_effective_time_value',
        'validity_time_type',
        'validity_time_value',
        'usage_rules',
        'receive_rules',
        'sort',
        'remarks',
        'total_quantity',
        'total_issued',
        'total_used',
    ];

    protected $appends = [
        'validity_time', 'delayed_effective_time'
    ];


    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->total_issued = 0;
            $instance->total_used   = 0;
        }

        return $instance;
    }

    /**
     * 用户优惠券关联
     */
    public function userCoupons() : HasMany
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id');
    }

    /**
     * 使用记录关联
     */
    public function usages() : HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    /**
     * 发放统计关联
     */
    public function issueStatistics() : HasMany
    {
        return $this->hasMany(CouponIssueStatistic::class, 'coupon_id');
    }


    /**
     * 检查是否在有效期内
     */
    public function isValid() : bool
    {
        $now = Carbon::now();
        if ($this->start_time && $now->lt($this->start_time)) {
            return false;
        }
        if ($this->end_time && $now->gt($this->end_time)) {
            return false;
        }

        return true;
    }




    public function getSellerUsageRules() : array
    {
        return [
            [
                'ruleType'    => RuleTypeEnum::INCLUDE,
                'objectType'  => RuleObjectTypeEnum::SELLER,
                'objectValue' => $this->owner_type.'|'.$this->owner_id,
            ]
        ];
    }

    /**
     * 检查使用规则
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return bool
     */
    public function checkUsageRules(ProductPurchaseFactor $productPurchaseFactor) : bool
    {

        return app(CouponRuleService::class)->checkUsageRules($this, $productPurchaseFactor);


    }


    /**
     * 是否可以达到门槛值
     *
     * @param  Money  $money
     * @param  int  $quantity
     *
     * @return bool
     */
    public function isReachedThreshold(Money $money, int $quantity) : bool
    {
        if ($this->threshold_type === ThresholdTypeEnum::QUANTITY) {
            if ($quantity < $this->coupon->threshold_value) {
                return false;
            }
        }

        if ($this->threshold_type === ThresholdTypeEnum::AMOUNT) {
            $productAmountValue   = $money->getAmount();
            $thresholdAmountValue = bcmul($this->threshold_value, 100, 2);
            if (bccomp($productAmountValue, $thresholdAmountValue, 2) < 0) {
                return false;
            }
        }
        return true;
    }

    // 获取优惠金额
    public function calculateDiscountAmount(Money $amount) : Money
    {

        if ($this->discount_amount_type === DiscountAmountTypeEnum::FIXED_AMOUNT) {
            return Money::parse($this->discount_amount_value, $amount->getCurrency());
        }
        if ($this->discount_amount_type === DiscountAmountTypeEnum::PERCENTAGE) {
            $discountAmount = $amount->multiply(bcsub(1, bcdiv($this->discount_amount_value, 100, 4), 4));

            // 如果设置最大优惠金额
            if (bccomp($this->max_discount_amount, 0, 2) > 0) {
                $maxDiscountAmount = Money::parse($this->max_discount_amount, $discountAmount->getCurrency());
                if ($maxDiscountAmount < $discountAmount) {
                    $discountAmount = $maxDiscountAmount;
                }
            }
            return $discountAmount;
        }
        return Money::parse(0, $amount->getCurrency());
    }

    /**
     * 检查是否可以领取
     */
    public function canReceive(PurchaseFactor $purchaseFactor) : bool
    {

        // 检查是否可以发放
        if (!$this->canIssue()) {
            return false;
        }

        // 检查领取规则
        if (!$this->checkReceiveRules($purchaseFactor)) {
            return false;
        }

        return true;
    }

    /**
     * 检查是否可以发放
     */
    public function canIssue() : bool
    {
        // 检查状态
        if ($this->status !== CouponStatusEnum::PUBLISHED) {
            return false;
        }

        // 检查有效期
        if (!$this->isValid()) {
            return false;
        }

        // 检查发放限制
        if ($this->total_quantity && $this->total_issued >= $this->total_quantity) {
            return false;
        }

        return true;
    }


    /**
     * @param  PurchaseFactor  $purchaseFactor
     *
     * @return bool
     */
    public function checkReceiveRules(PurchaseFactor $purchaseFactor) : bool
    {

        return app(CouponRuleService::class)->checkReceiveRules($this, $purchaseFactor);
    }


    /**
     * 获取优惠券的有效期
     * @return \Illuminate\Support\Carbon[]
     */
    public function buildUserCouponValidityTimes() : array
    {
        if ($this->validity_type === ValidityTypeEnum::ABSOLUTE) {

            return [
                $this->validity_start_time,
                $this->validity_end_time,
            ];
        }
        $startTime = $now = \Illuminate\Support\Carbon::now();
        if (!$this->delayed_effective_time) {
            $startTime = $this->delayed_effective_time->afterAt($now);
        }


        $endTime = $this->validity_time->afterAt($startTime);
        return [$startTime, $endTime];
    }


    public function canPublish() : bool
    {
        return $this->isAllowUpdateStatus(CouponStatusEnum::PUBLISHED);
    }


    /**
     * 发布优惠券
     * @return void
     * @throws CouponException
     */
    public function publish() : void
    {
        $this->updateStatus(CouponStatusEnum::PUBLISHED);

        $this->published_time = \Illuminate\Support\Carbon::now();

        $this->fireModelEvent('publish', false);
    }

    /**
     * 更新状态
     */
    public function updateStatus(CouponStatusEnum $status) : void
    {
        if (!$this->isAllowUpdateStatus($status)) {
            throw new CouponException('当前状态不允许更新为：'.$status->value);
        }

        $this->status = $status;
    }

    /**
     * 检查是否允许更新状态
     */
    private function isAllowUpdateStatus(CouponStatusEnum $status) : bool
    {
        return match ($this->status) {
            CouponStatusEnum::DRAFT, CouponStatusEnum::PAUSED => in_array($status, [CouponStatusEnum::PUBLISHED, CouponStatusEnum::EXPIRED]),
            CouponStatusEnum::PUBLISHED => in_array($status, [CouponStatusEnum::PAUSED, CouponStatusEnum::EXPIRED]),
            CouponStatusEnum::EXPIRED => false,
        };
    }

    /**
     * 暂停优惠券
     * @return void
     * @throws CouponException
     */
    public function pause() : void
    {
        $this->updateStatus(CouponStatusEnum::PAUSED);
        $this->fireModelEvent('pause');
    }

    /**
     * 过期优惠券
     * @return void
     * @throws CouponException
     */
    public function expire() : void
    {
        $this->updateStatus(CouponStatusEnum::EXPIRED);

        $this->fireModelEvent('pause');
    }

    /**
     * 获取剩余可发放数量
     */
    public function getRemainingIssueCount() : ?int
    {
        if (!$this->total_quantity) {
            return null;
        }

        return max(0, $this->total_quantity - $this->total_issued);
    }

    /**
     * 是否已达到发放限制
     */
    public function isIssueLimitReached() : bool
    {
        return $this->total_quantity && $this->total_issued >= $this->total_quantity;
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(float $amount) : float
    {
        if ($amount < $this->threshold_value) {
            return 0;
        }

        $discount = match ($this->discount_amount_type) {
            DiscountAmountTypeEnum::FIXED_AMOUNT => $this->discount_amount_value,
            DiscountAmountTypeEnum::PERCENTAGE => $amount * ($this->discount_amount_value / 100),
        };

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }

    /**
     * 获取优惠券标签描述
     *
     * @return string
     */
    public function getLabelAttribute() : string
    {
        // 门槛描述
        $thresholdText = match ($this->threshold_type) {
            ThresholdTypeEnum::AMOUNT => __('red-jasmine-coupon::coupon.label.threshold.amount_over', [
                'amount' => $this->formatNumber($this->threshold_value)
            ]),
            ThresholdTypeEnum::QUANTITY => __('red-jasmine-coupon::coupon.label.threshold.quantity_over', [
                'quantity' => $this->formatNumber($this->threshold_value)
            ]),
        };

        // 优惠描述
        $discountText = match ($this->discount_amount_type) {
            DiscountAmountTypeEnum::FIXED_AMOUNT => match ($this->threshold_type) {
                ThresholdTypeEnum::AMOUNT => __('red-jasmine-coupon::coupon.label.discount.fixed_amount', [
                    'amount' => $this->formatNumber($this->discount_amount_value)
                ]),
                ThresholdTypeEnum::QUANTITY => __('red-jasmine-coupon::coupon.label.discount.fixed_amount_yuan', [
                    'amount' => $this->formatNumber($this->discount_amount_value)
                ]),
            },
            DiscountAmountTypeEnum::PERCENTAGE => __('red-jasmine-coupon::coupon.label.discount.percentage', [
                'rate' => $this->getDiscountDisplayValue()
            ]),
        };

        return $thresholdText.$discountText;
    }

    /**
     * 格式化数字显示（去除不必要的小数0）
     *
     * @param  float  $number
     *
     * @return string
     */
    protected function formatNumber(float $number) : string
    {
        // 如果是整数（小数部分为0），只显示整数部分
        if ($number == floor($number)) {
            return (string) (int) $number;
        }

        // 保留两位小数，但去除尾部的0
        return rtrim(rtrim(number_format($number, 2), '0'), '.');
    }

    /**
     * 获取折扣显示值（根据语言环境返回不同格式）
     *
     * @return string
     */
    protected function getDiscountDisplayValue() : string
    {
        $locale = app()->getLocale();

        // 中文环境：打8折
        if (in_array($locale, ['zh', 'zh-CN', 'zh-TW'])) {
            return $this->getDiscountRate();
        }

        // 英文环境：20% off
        return $this->formatNumber($this->discount_amount_value);
    }

    /**
     * 获取折扣率（用于显示）
     *
     * @return string
     */
    protected function getDiscountRate() : string
    {
        $rate = (100 - $this->discount_amount_value) / 10;

        // 如果是整数，不显示小数点
        if ($rate == floor($rate)) {
            return (string) (int) $rate;
        }

        // 保留一位小数
        return number_format($rate, 1);
    }

    public function scopeUserVisible(Builder $query)
    {
        return $query->where('status', CouponStatusEnum::PAUSED)
                     ->where('is_show', true);
    }

    protected function casts() : array
    {
        return [
            'coupon_type'            => CouponTypeEnum::class,
            'status'                 => CouponStatusEnum::class,
            'is_show'                => 'boolean',
            'discount_level'         => DiscountLevelEnum::class,
            'discount_amount_type'   => DiscountAmountTypeEnum::class,
            'discount_amount_value'  => 'decimal:2',
            'threshold_type'         => ThresholdTypeEnum::class,
            'threshold_value'        => 'decimal:2',
            'max_discount_amount'    => 'decimal:2',
            'validity_type'          => ValidityTypeEnum::class,
            'validity_start_time'    => 'datetime',
            'validity_end_time'      => 'datetime',
            'start_time'             => 'datetime',
            'end_time'               => 'datetime',
            'delayed_effective_time' => TimeConfigCast::class,
            'validity_time'          => TimeConfigCast::class,
            'usage_rules'            => 'array',
            'receive_rules'          => 'array',
            'sort'                   => 'integer',
            'total_quantity'         => 'integer',
            'total_issued'           => 'integer',
            'total_used'             => 'integer',
            'cost_bearer'            => UserInterfaceCast::class,
        ];
    }


    public function use() : void
    {
        $this->increment('total_used');


    }

}