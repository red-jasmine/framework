<?php

namespace RedJasmine\Shopping\Domain\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var Amount
     */
    public Amount $totalPayableAmount;


    /**
     * @var Collection<OrderData>
     */
    public Collection $orders;
}
