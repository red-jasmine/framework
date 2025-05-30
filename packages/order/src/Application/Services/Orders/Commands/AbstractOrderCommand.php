<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class AbstractOrderCommand extends Data
{

    public string $orderNo;
    public ?int    $id;

}