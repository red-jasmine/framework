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
use RedJasmine\Coupon\Domain\Models\ValueObjects\DiscountRule;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

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

    protected function casts() : array
    {
        return [
            'status'                      => CouponStatusEnum::class,
            'is_show'                     => 'boolean',
            'discount_target'             => DiscountTargetEnum::class,
            'threshold_type'              => ThresholdTypeEnum::class,
            'threshold_value'             => 'decimal:2',
            'discount_type'               => DiscountAmountTypeEnum::class,
            'discount_value'              => 'decimal:2',
            'max_discount_amount'         => 'decimal:2',
            'validity_type'               => ValidityTypeEnum::class,
            'validity_start_time'         => 'datetime',
            'validity_end_time'           => 'datetime',
            'validity_time_type'          => TimeUnitEnum::class,
            'delayed_effective_time_type' => TimeUnitEnum::class,
            'usage_rules'                 => 'array',
            'receive_rules'               => 'array',
            'total_quantity'              => 'integer',
            'total_issued'                => 'integer',
            'total_used'                  => 'integer',
        ];
    }


    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();
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
     * 检查是否可以发放
     */
    public function canIssue() : bool
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
    public function canUse(array $context = []) : bool
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
    public function canCollect(array $context = []) : bool
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
        if (!$this->total_issue_limit) {
            return null;
        }

        return max(0, $this->total_issue_limit - $this->current_issue_count);
    }

    /**
     * 是否已达到发放限制
     */
    public function isIssueLimitReached() : bool
    {
        return $this->total_issue_limit && $this->current_issue_count >= $this->total_issue_limit;
    }
} 