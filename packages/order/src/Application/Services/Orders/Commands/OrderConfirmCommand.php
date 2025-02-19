<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderConfirmCommand extends Data
{


    public int $id;

    // 分开确认
    public ?int $orderProductId = null;


}
