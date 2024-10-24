<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Others;

use RedJasmine\Support\Data\Data;

class OrderStarCommand extends Data
{
    public int $id;

    public ?int $star = null;


}
