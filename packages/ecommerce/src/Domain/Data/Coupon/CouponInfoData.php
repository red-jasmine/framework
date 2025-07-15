<?php

namespace RedJasmine\Ecommerce\Domain\Data\Coupon;

use Cknow\Money\Money;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

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