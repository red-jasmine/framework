<?php

namespace RedJasmine\Ecommerce\Domain\Data\Coupon;

use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 优惠券
 */
class CouponInfoData extends Data
{


    public int $couponId;


    public string $label;

    public string $couponNo;

    /**
     * 优惠券级别
     * @var DiscountLevelEnum
     */
    public DiscountLevelEnum $discountLevel;

    public Money $discountAmount;

    /**
     * 成本承担方
     */
    public UserInterface $costBearer;


    /**
     * 分摊比例
     * @var array
     */
    public array $proportions = [];

}