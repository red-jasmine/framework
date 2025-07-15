<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class CouponCanUseResult extends Data
{

    public bool $isCanUse = false;


    public Money $amount;


    public int $quantity;

}