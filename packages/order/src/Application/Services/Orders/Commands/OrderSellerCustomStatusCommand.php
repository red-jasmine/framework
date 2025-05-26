<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderSellerCustomStatusCommand extends AbstractOrderCommand
{


    public ?int $orderProductId = null;

    public string $sellerCustomStatus;
}
