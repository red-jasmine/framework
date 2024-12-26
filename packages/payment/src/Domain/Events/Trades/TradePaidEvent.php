<?php

namespace RedJasmine\Payment\Domain\Events\Trades;

use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;
use RedJasmine\Payment\Domain\Data\NotifyData;

class TradePaidEvent extends AbstractTradeEvent implements AsyncNotifyInterface
{
    public function getAsyncNotify() : ?NotifyData
    {
        return $this->trade->getAsyncNotify();
    }


}
