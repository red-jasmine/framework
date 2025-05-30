<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

class OrderSellerCustomStatusCommand extends AbstractOrderCommand
{


    public ?string $orderProductNo = null;

    public string $sellerCustomStatus;
}
