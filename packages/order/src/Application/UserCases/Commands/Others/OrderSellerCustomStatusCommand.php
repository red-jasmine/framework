<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Others;

use RedJasmine\Support\Data\Data;

class OrderSellerCustomStatusCommand extends Data
{
    public int $id;

    public ?int $orderProductId = null;

    public string $sellerCustomStatus;
}
