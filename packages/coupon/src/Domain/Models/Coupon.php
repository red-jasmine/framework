<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTargetEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\TimeConfigCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property ?TimeConfigCast $delayed_effective_time
 * @property ?TimeConfigCast $validity_time
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
        'discount_target',
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

  protected $appends  = [
      'validity_time','delayed_effective_time'
  ];

    protected function casts() : array
    {
        return [
            'status'                 => CouponStatusEnum::class,
            'is_show'                => 'boolean',
            'discount_target'        => DiscountTargetEnum::class,
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
     * 检查是否可以使用
     */
    public function canUse(array $context = []) : bool
    {
        // 检查状态
        if ($this->status !== CouponStatusEnum::PUBLISHED) {
            return false;
        }

        // 检查有效期
        if (!$this->isValid()) {
            return false;
        }

        // 检查使用规则
        if ($this->usage_rules && !$this->checkUsageRules($context)) {
            return false;
        }

        return true;
    }

    /**
     * 检查是否可以领取
     */
    public function canCollect(array $context = []) : bool
    {
        // 检查是否可以发放
        if (!$this->canIssue()) {
            return false;
        }

        // 检查领取规则
        if ($this->receive_rules && !$this->checkReceiveRules($context)) {
            return false;
        }

        return true;
    }

    /**
     * 检查是否在有效期内
     */
    public function isValid() : bool
    {
        $now = Carbon::now();

        if ($this->validity_type === ValidityTypeEnum::ABSOLUTE) {
            if ($this->validity_start_time && $now->lt($this->validity_start_time)) {
                return false;
            }
            if ($this->validity_end_time && $now->gt($this->validity_end_time)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 检查使用规则
     */
    protected function checkUsageRules(array $context) : bool
    {
        // TODO: 实现使用规则检查逻辑
        return true;
    }

    /**
     * 检查领取规则
     */
    protected function checkReceiveRules(array $context) : bool
    {
        // TODO: 实现领取规则检查逻辑
        return true;
    }

    /**
     * 发放给用户
     */
    public function issueToUser(int $userId) : UserCoupon
    {
        if (!$this->canIssue()) {
            throw new CouponException('优惠券不能发放');
        }

        $userCoupon = new UserCoupon([
            'coupon_id'   => $this->id,
            'user_id'     => $userId,
            'issue_time'  => Carbon::now(),
            'expire_time' => $this->getExpireTime(),
        ]);

        $userCoupon->save();

        // 更新发放数量
        $this->increment('total_issued');

        return $userCoupon;
    }

    /**
     * 获取过期时间
     */
    public function getExpireTime() : Carbon
    {
        if ($this->validity_type === ValidityTypeEnum::ABSOLUTE) {
            return $this->validity_end_time ?? Carbon::now()->addDays(30);
        }

        // 相对时间计算
        $now = Carbon::now();
        if ($this->validity_time_type === TimeUnitEnum::DAY) {
            return $now->addDays($this->validity_time_value);
        } elseif ($this->validity_time_type === TimeUnitEnum::HOUR) {
            return $now->addHours($this->validity_time_value);
        } elseif ($this->validity_time_type === TimeUnitEnum::MINUTE) {
            return $now->addMinutes($this->validity_time_value);
        }

        return $now->addDays(30);
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
     * 发布优惠券
     */
    public function publish() : void
    {
        $this->updateStatus(CouponStatusEnum::PUBLISHED);
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
    public function getLabelAttribute(): string
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

        return $thresholdText . $discountText;
    }

    /**
     * 获取折扣率（用于显示）
     * 
     * @return string
     */
    protected function getDiscountRate(): string
    {
        $rate = (100 - $this->discount_amount_value) / 10;
        
        // 如果是整数，不显示小数点
        if ($rate == floor($rate)) {
            return (string) (int) $rate;
        }
        
        // 保留一位小数
        return number_format($rate, 1);
    }

    /**
     * 获取折扣显示值（根据语言环境返回不同格式）
     * 
     * @return string
     */
    protected function getDiscountDisplayValue(): string
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
     * 格式化数字显示（去除不必要的小数0）
     * 
     * @param float $number
     * @return string
     */
    protected function formatNumber(float $number): string
    {
        // 如果是整数（小数部分为0），只显示整数部分
        if ($number == floor($number)) {
            return (string) (int) $number;
        }
        
        // 保留两位小数，但去除尾部的0
        return rtrim(rtrim(number_format($number, 2), '0'), '.');
    }


}