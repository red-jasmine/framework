<?php

namespace RedJasmine\Shopping\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 优惠券
 */
class CouponInfoData extends Data
{


    public string $label;

    public string $couponNo;

    public Money $discountAmount;


    /**
     * 成本承担方
     */
    public UserInterface $costBearer;

}