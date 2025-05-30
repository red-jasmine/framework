<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Data\CardKeyData;

class OrderCardKeyShippingCommand extends CardKeyData
{


    public ?string $orderNo;

    public string $orderProductNo;


}
