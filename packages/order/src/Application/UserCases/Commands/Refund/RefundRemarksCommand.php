<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundRemarksCommand extends Data
{
    public int $rid;

    public string $remarks;
    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
