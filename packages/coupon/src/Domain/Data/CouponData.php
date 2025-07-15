<?php

namespace RedJasmine\Coupon\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

class CouponData extends Data
{
    public UserInterface $owner;

    public string $name;

    public ?string $description = null;

    public ?string $image = null;

    public bool $isShow = true;

    /**
     * 开始时间
     */
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public ?Carbon $startTime = null;

    /**
     * 发布时间
     * @var Carbon|null
     */
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public ?Carbon $endTime = null;


    #[WithCast(EnumCast::class, CouponStatusEnum::class)]
    public CouponStatusEnum $status = CouponStatusEnum::DRAFT;

    /**
     * 优惠目标
     */
    #[WithCast(EnumCast::class, DiscountLevelEnum::class)]
    public DiscountLevelEnum $discountLevel = DiscountLevelEnum::ORDER;

    /**
     * 优惠金额类型
     */
    #[WithCast(EnumCast::class, DiscountAmountTypeEnum::class)]
    public DiscountAmountTypeEnum $discountAmountType = DiscountAmountTypeEnum::PERCENTAGE;

    /**
     * 优惠金额值
     */
    public float $discountAmountValue = 0;

    /**
     * 门槛类型
     */
    #[WithCast(EnumCast::class, ThresholdTypeEnum::class)]
    public ThresholdTypeEnum $thresholdType = ThresholdTypeEnum::AMOUNT;

    /**
     * 门槛值
     */
    public float $thresholdValue = 0;

    /**
     * 最大优惠金额
     */
    public float $maxDiscountAmount = 0;

    /**
     * 有效期类型
     */
    #[WithCast(EnumCast::class, ValidityTypeEnum::class)]
    public ValidityTypeEnum $validityType = ValidityTypeEnum::ABSOLUTE;

    /**
     * 绝对生效时间
     */
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public ?Carbon $validityStartTime = null;
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public ?Carbon $validityEndTime = null;


    /**
     * 延迟生效时间
     * @var TimeConfigData|null
     */
    public ?TimeConfigData $delayedEffectiveTime;

    /**
     * 相对有效期
     * @var TimeConfigData|null
     */
    public ?TimeConfigData $validityTime;

    /**
     * 使用规则
     */
    public ?array $usageRules = [];

    /**
     * 领取规则
     */
    public ?array $receiveRules = [];

    /**
     * 成本承担方
     */
    public ?UserInterface $costBearer = null;

    public int $sort = 0;

    public ?string $remarks = null;

    /**
     * 总数量
     */
    public int $totalQuantity = 100;

    /**
     * 定义验证规则
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'isShow' => ['boolean'],
            'startTime' => ['nullable', 'date'],
            'endTime' => ['nullable', 'date'],
            'status' => ['required'],
            'discountLevel' => ['required'],
            'discountAmountType' => ['required'],
            'discountAmountValue' => ['required', 'numeric', 'min:0'],
            'thresholdType' => ['required'],
            'thresholdValue' => ['required', 'numeric', 'min:0'],
            'maxDiscountAmount' => ['required', 'numeric', 'min:0'],
            'validityType' => ['required'],
            'validityStartTime' => ['nullable', 'date'],
            'validityEndTime' => ['nullable', 'date'],
            'usageRules' => ['array'],
            'receiveRules' => ['array'],
            'sort' => ['integer', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'totalQuantity' => ['integer', 'min:1'],
        ];
    }

    /**
     * 业务逻辑验证
     */
    public function validateBusinessRules(): void
    {
        $this->validateValidityTime();
        $this->validateStartAndEndTime();
        $this->validateDiscountAmount();
    }

    /**
     * 验证有效期时间
     */
    protected function validateValidityTime(): void
    {
        // 当有效期为绝对时间时，有效期开始时间和结束时间不能为空
        if ($this->validityType === ValidityTypeEnum::ABSOLUTE) {
            if (empty($this->validityStartTime)) {
                throw new CouponException('当有效期为绝对时间时，有效期开始时间不能为空');
            }
            
            if (empty($this->validityEndTime)) {
                throw new CouponException('当有效期为绝对时间时，有效期结束时间不能为空');
            }
            
            // 结束时间必须大于开始时间
            if ($this->validityEndTime <= $this->validityStartTime) {
                throw new CouponException('有效期结束时间必须大于开始时间');
            }
        }
    }

    /**
     * 验证开始时间和结束时间
     */
    protected function validateStartAndEndTime(): void
    {
        // 优惠券的开始时间必须小于结束时间
        if ($this->startTime && $this->endTime) {
            if ($this->startTime >= $this->endTime) {
                throw new CouponException('优惠券开始时间必须小于结束时间');
            }
        }
    }

    /**
     * 验证优惠金额
     */
    protected function validateDiscountAmount(): void
    {
        // 当门槛类型为金额时
        if ($this->thresholdType === ThresholdTypeEnum::AMOUNT) {
            // 当优惠金额类型为固定金额时，优惠金额值不能大于门槛金额值
            if ($this->discountAmountType === DiscountAmountTypeEnum::FIXED_AMOUNT) {
                if ($this->discountAmountValue > $this->thresholdValue) {
                    throw new CouponException('固定金额优惠不能大于门槛金额');
                }
            }
        }
        
        // 当优惠金额类型为折扣比时，优惠金额值不能大于100
        if ($this->discountAmountType === DiscountAmountTypeEnum::PERCENTAGE) {
            if ($this->discountAmountValue > 100) {
                throw new CouponException('折扣比例不能大于100%');
            }
        }
    }
}