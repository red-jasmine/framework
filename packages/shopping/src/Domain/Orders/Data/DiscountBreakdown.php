<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

/**
 * 优惠明细
 */
class DiscountBreakdown extends Data
{

    public Money $total;

}
