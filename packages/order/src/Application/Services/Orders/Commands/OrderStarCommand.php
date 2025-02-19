<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class OrderStarCommand extends Data
{
    public int $id;

    public ?int $star = null;


}
