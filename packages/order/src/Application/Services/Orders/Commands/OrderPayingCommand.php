<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Money\Data\Money;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;

class OrderPayingCommand extends AbstractOrderCommand
{

    public ?Money         $amount;
    public AmountTypeEnum $amountType = AmountTypeEnum::FULL;

}
