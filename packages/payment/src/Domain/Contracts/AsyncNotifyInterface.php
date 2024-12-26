<?php

namespace RedJasmine\Payment\Domain\Contracts;

use RedJasmine\Payment\Domain\Data\NotifyData;

/**
 * 异步通知
 */
interface AsyncNotifyInterface
{

    public function getAsyncNotify() : ?NotifyData;

}
