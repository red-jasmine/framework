<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundRemarksCommand extends Data
{
    public int $id;

    public ?string $remarks = '';
    /**
     * 是否追加模式
     * @var bool
     */
    public bool $isAppend = false;

}
