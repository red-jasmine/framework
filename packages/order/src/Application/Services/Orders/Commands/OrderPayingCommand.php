<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class OrderPayingCommand extends Data
{
    public int            $id;
    public ?Money         $amount;
    public AmountTypeEnum $amountType = AmountTypeEnum::FULL;

}
