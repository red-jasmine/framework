<?php

namespace RedJasmine\Coupon\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Coupon\Domain\Models\Casts\CollectRuleCast;
use RedJasmine\Coupon\Domain\Models\Casts\LadderRulesCast;
use RedJasmine\Coupon\Domain\Models\Casts\UsageRuleCast;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\IssueStrategyEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Domain\Models\ValueObjects\CollectRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\CostBearer;
use RedJasmine\Coupon\Domain\Models\ValueObjects\DiscountRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\UsageRule;
use RedJasmine\Coupon\Domain\Models\ValueObjects\ValidityRule;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class Coupon extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'coupons';

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'is_ladder',
        'ladder_rules',
        'threshold_type',
        'threshold_value',
        'is_threshold_required',
        'validity_type',
        'start_time',
        'end_time',
        'relative_days',
        'max_usage_per_user',
        'max_usage_total',
        'usage_rules',
        'collect_rules',
        'cost_bearer_type',
        'cost_bearer_id',
        'cost_bearer_name',
        'issue_strategy',
        'total_issue_limit',
        'current_issue_count',
    ];

    protected function casts(): array
    {
        return [
            'status' => CouponStatusEnum::class,
            'discount_type' => DiscountTypeEnum::class,
            'discount_value' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'is_ladder' => 'boolean',
            'ladder_rules' => LadderRulesCast::class,
            'threshold_type' => ThresholdTypeEnum::class,
            'threshold_value' => 'decimal:2',
            'is_threshold_required' => 'boolean',
            'validity_type' => ValidityTypeEnum::class,
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'max_usage_per_user' => 'integer',
            'max_usage_total' => 'integer',
            'usage_rules' => UsageRuleCast::class,
            'collect_rules' => CollectRuleCast::class,
            'issue_strategy' => IssueStrategyEnum::class,
            'total_issue_limit' => 'integer',
            'current_issue_count' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Coupon $coupon) {
            $coupon->setUniqueIds();
            $coupon->status = CouponStatusEnum::DRAFT;
            $coupon->current_issue_count = 0;
        });
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->status = CouponStatusEnum::DRAFT;
            $instance->current_issue_count = 0;
        }

        return $instance;
    }

    /**
     * 用户优惠券关联
     */
    public function userCoupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id');
    }

    /**
     * 使用记录关联
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }


    /**
     * 优惠规则访问器
     */
    public function getDiscountRuleAttribute(): DiscountRule
    {
        return new DiscountRule([
            'thresholdType' => $this->threshold_type,
            'thresholdValue' => $this->threshold_value,
            'isThresholdRequired' => $this->is_threshold_required,
            'discountType' => $this->discount_type,
            'discountValue' => $this->discount_value,
            'maxDiscountAmount' => $this->max_discount_amount,
            'isLadder' => $this->is_ladder,
            'ladderRules' => $this->ladder_rules ?? [],
        ]);
    }

    /**
     * 有效期规则访问器
     */
    public function getValidityRuleAttribute(): ValidityRule
    {
        return new ValidityRule([
            'validityType' => $this->validity_type,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'relativeDays' => $this->relative_days,
        ]);
    }

    /**
     * 成本承担方访问器
     */
    public function getCostBearerAttribute(): CostBearer
    {
        return new CostBearer([
            'type' => $this->cost_bearer_type,
            'id' => $this->cost_bearer_id,
            'name' => $this->cost_bearer_name,
        ]);
    }

    /**
     * 检查是否可以发放
     */
    public function canIssue(): bool
    {
        // 检查状态
        if ($this->status !== CouponStatusEnum::PUBLISHED) {
            return false;
        }

        // 检查有效期
        if (!$this->validityRule->isValid()) {
            return false;
        }

        // 检查发放限制
        if ($this->total_issue_limit && $this->current_issue_count >= $this->total_issue_limit) {
            return false;
        }

        return true;
    }

    /**
     * 检查是否可以使用
     */
    public function canUse(array $context = []): bool
    {
        // 检查状态
        if ($this->status !== CouponStatusEnum::PUBLISHED) {
            return false;
        }

        // 检查有效期
        if (!$this->validityRule->isValid()) {
            return false;
        }

        // 检查使用规则
        if ($this->usage_rules && !$this->usage_rules->canUse($context)) {
            return false;
        }

        return true;
    }

    /**
     * 检查是否可以领取
     */
    public function canCollect(array $context = []): bool
    {
        // 检查是否可以发放
        if (!$this->canIssue()) {
            return false;
        }

        // 检查领取规则
        if ($this->collect_rules && !$this->collect_rules->canCollect($context)) {
            return false;
        }

        return true;
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(float $amount): float
    {
        return $this->discountRule->calculateDiscount($amount);
    }

    /**
     * 发放给用户
     */
    public function issueToUser(int $userId): UserCoupon
    {
        if (!$this->canIssue()) {
            throw new CouponException('优惠券不能发放');
        }

        $userCoupon = new UserCoupon([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'issue_time' => Carbon::now(),
            'expire_time' => $this->validityRule->getExpireTime(),
        ]);

        $userCoupon->save();

        // 更新发放数量
        $this->increment('current_issue_count');

        return $userCoupon;
    }

    /**
     * 更新状态
     */
    public function updateStatus(CouponStatusEnum $status): void
    {
        if (!$this->isAllowUpdateStatus($status)) {
            throw new CouponException('当前状态不允许更新为：' . $status->value);
        }

        $this->status = $status;
        $this->save();
    }

    /**
     * 检查是否允许更新状态
     */
    private function isAllowUpdateStatus(CouponStatusEnum $status): bool
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
    public function publish(): void
    {
        $this->updateStatus(CouponStatusEnum::PUBLISHED);
    }

    /**
     * 暂停优惠券
     */
    public function pause(): void
    {
        $this->updateStatus(CouponStatusEnum::PAUSED);
    }

    /**
     * 过期优惠券
     */
    public function expire(): void
    {
        $this->updateStatus(CouponStatusEnum::EXPIRED);
    }

    /**
     * 获取显示名称
     */
    public function getDisplayName(): string
    {
        return $this->name . ' - ' . $this->discountRule->getDisplayText();
    }

    /**
     * 获取剩余可发放数量
     */
    public function getRemainingIssueCount(): ?int
    {
        if (!$this->total_issue_limit) {
            return null;
        }

        return max(0, $this->total_issue_limit - $this->current_issue_count);
    }

    /**
     * 是否已达到发放限制
     */
    public function isIssueLimitReached(): bool
    {
        return $this->total_issue_limit && $this->current_issue_count >= $this->total_issue_limit;
    }
} 