<?php

namespace RedJasmine\Ecommerce\Domain\Data\Order;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 优惠明细
 */
class DiscountBreakdown extends Data
{

    public Money $total;

}
