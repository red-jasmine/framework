<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderCancelCommand extends Data
{

    public int     $id;
    public ?string $cancelReason = null;


}
