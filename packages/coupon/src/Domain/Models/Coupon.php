<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Domain\Models\ValueObjects\RuleItem;
use RedJasmine\Coupon\Domain\Models\ValueObjects\RuleValue;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\System;
use RedJasmine\Support\Domain\Casts\TimeConfigCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
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

    protected static function boot() : void
    {
        parent::boot();

        // 保存时处理关联关系
        static::saving(function (Coupon $coupon) {
            // 处理成本承担方关联
            if ($coupon->relationLoaded('costBearer')) {
                $coupon->cost_bearer_type = $coupon->costBearer?->getType();
                $coupon->cost_bearer_id   = $coupon->costBearer?->getID();
            }
        });
    }

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


    /**
     * 判断是否为系统优惠券
     * @return bool
     */
    public function isSystem() : bool
    {
        $system = System::make();
        return $this->owner_type === $system->getType();
    }

    /**
     * 满足规则
     *
     * @param  array  $rules
     * @param  array  $factors
     *
     * @return bool
     */
    protected function meetRules(array $rules, array $factors) : bool
    {
        $ruleItems    = collect(RuleItem::collect($rules));
        $factors      = collect(RuleValue::collect($factors));
        $factorGroups = $factors->groupBy('objectType')->all();


        // 命中排除规则
        /**
         * @var RuleItem $ruleItem
         */

        if (array_any($ruleItems->where('ruleType', RuleTypeEnum::EXCLUDE)->all(),
            fn($ruleItem) => array_any($factorGroups[$ruleItem->objectType->value]?->all() ?? [],
                fn($ruleFactor) => $ruleItem->matches($ruleFactor->objectType, $ruleFactor->objectValue)))) {
            return false;
        }

        // 然后对包含规则再次分组
        $includeRules = $ruleItems->where('ruleType', RuleTypeEnum::INCLUDE)->groupBy('objectType')->all();

        // 同  objectType 下  或的关系、 不同  objectType 下 需 全部满足
        $isMeet = true;

        foreach ($includeRules as $objectType => $objectTypeRules) {
            $objectTypeMet = false;
            foreach ($objectTypeRules as $ruleItem) {
                foreach ($factorGroups[$objectType] ?? [] as $factor) {
                    if ($ruleItem->matches($factor->objectType, $factor->objectValue)) {
                        $objectTypeMet = true;
                        break; // 找到匹配项后跳出内层循环
                    }
                }

                if ($objectTypeMet) {
                    break; // 找到匹配规则后跳出当前 objectType 的处理
                }
            }

            if (!$objectTypeMet) {
                $isMeet = false;
                break; // 只要有一个 objectType 不满足条件，整体就不满足
            }
        }
        return $isMeet;
    }

    protected function getSellerUsageRules() : array
    {
        return [
            [
                'ruleType'    => RuleTypeEnum::INCLUDE,
                'objectType'  => RuleObjectTypeEnum::SELLER,
                'objectValue' => $this->coupon->owner_type.'|'.$this->coupon->owner_id,
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
        // TODO 对规则进行验证
        // 获取当前规格
        $this->usage_rules;


        // 如果不是系统券时
        if (!$this->isSystem()) {
            $shopRules         = RuleItem::collect($this->getSellerUsageRules());
            $factorSellerValue = $productPurchaseFactor->getProductInfo()->product->seller->getType()
                                 .'|'.
                                 $productPurchaseFactor->getProductInfo()->product->seller->getID();
            if (!$this->meetRules($shopRules, [['objectType' => RuleObjectTypeEnum::SELLER, 'objectValue' => $factorSellerValue,]])) {
                return false;
            }
        }

        $ruleFactors = [];

        $ruleFactors[] = [
            'objectType'  => RuleObjectTypeEnum::PRODUCT,
            'objectValue' => $productPurchaseFactor->getProductInfo()->product->id,
        ];
        $ruleFactors[] = [
            'objectType'  => RuleObjectTypeEnum::BRAND,
            'objectValue' => $productPurchaseFactor->getProductInfo()->brandId,
        ];
        $ruleFactors[] = [
            'objectType'  => RuleObjectTypeEnum::CATEGORY,
            'objectValue' => $productPurchaseFactor->getProductInfo()->categoryId,
        ];


        return $this->meetRules($this->usage_rules, $ruleFactors);

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
        if ($this->receive_rules && !$this->checkReceiveRules($purchaseFactor)) {
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
    protected function checkReceiveRules(PurchaseFactor $purchaseFactor) : bool
    {
        // TODO
        // 领取规则的验证
        // 每人总数量
        // 每人当前数量
        // 必须为  VIP


        return true;
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

    /**
     * 发布优惠券
     */
    public function publish() : void
    {
        $this->updateStatus(CouponStatusEnum::PUBLISHED);
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
            CouponStatusEnum::DRAFT => in_array($status, [CouponStatusEnum::PUBLISHED, CouponStatusEnum::EXPIRED]),
            CouponStatusEnum::PUBLISHED => in_array($status, [CouponStatusEnum::PAUSED, CouponStatusEnum::EXPIRED]),
            CouponStatusEnum::PAUSED => in_array($status, [CouponStatusEnum::PUBLISHED, CouponStatusEnum::EXPIRED]),
            CouponStatusEnum::EXPIRED => false,
        };
    }

    /**
     * 暂停优惠券
     */
    public function pause() : void
    {
        $this->updateStatus(CouponStatusEnum::PAUSED);
    }

    /**
     * 过期优惠券
     */
    public function expire() : void
    {
        $this->updateStatus(CouponStatusEnum::EXPIRED);
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