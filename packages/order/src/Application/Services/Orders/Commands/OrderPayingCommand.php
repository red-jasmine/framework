<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Support\Data\Data;

class OrderPayingCommand extends Data
{
    /**
     * @param int    $id     订单ID
     * @param string $amount 金额
     */
    public function __construct(
        public int    $id,
        public string $amount,
        public AmountTypeEnum $amountType = AmountTypeEnum::FULL
    )
    {
    }


}
