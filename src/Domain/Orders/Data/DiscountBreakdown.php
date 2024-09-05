<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

/**
 * 优惠明细
 */
class DiscountBreakdown extends Data
{

    public Amount $total;

}
