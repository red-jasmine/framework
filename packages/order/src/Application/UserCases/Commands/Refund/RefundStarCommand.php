<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundStarCommand extends Data
{
    public int $id;

    public ?int $star = null;


}
