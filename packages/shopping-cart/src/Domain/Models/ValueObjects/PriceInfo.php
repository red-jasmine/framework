<?php

namespace RedJasmine\ShoppingCart\Domain\Models\ValueObjects;

use Cknow\Money\Money;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class PriceInfo extends ValueObject
{
    /**
     * 单价
     * @var Money
     */
    public Money $price;

    /**
     * 原价
     * @var Money
     */
    public Money $originalPrice;

    /**
     * 优惠金额
     * @var Money
     */
    public Money $discountAmount;




} 