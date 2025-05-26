<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use Cknow\Money\Money;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Support\Data\Data;

class OrderPayingCommand extends Data
{
    public ?int           $id;
    public ?string        $orderNo;
    public ?Money         $amount;
    public AmountTypeEnum $amountType = AmountTypeEnum::FULL;

}
