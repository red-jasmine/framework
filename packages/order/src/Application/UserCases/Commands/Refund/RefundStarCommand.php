<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundStarCommand extends Data
{
    public int $rid;

    public ?int $star = null;


}
