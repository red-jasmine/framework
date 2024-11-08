<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Shipping;

use RedJasmine\Order\Domain\Data\CardKeyData;

class OrderCardKeyShippingCommand extends CardKeyData
{
    public int $id;

    public int $orderProductId;


}
