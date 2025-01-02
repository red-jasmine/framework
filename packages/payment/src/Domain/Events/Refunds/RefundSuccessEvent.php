<?php

namespace RedJasmine\Payment\Domain\Events\Refunds;

use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;
use RedJasmine\Payment\Domain\Data\NotifyData;

/**
 * 退款处理中事件
 */
class RefundSuccessEvent extends AbstractRefundEvent implements AsyncNotifyInterface
{
    public function getAsyncNotify() : ?NotifyData
    {
        return $this->refund->getAsyncNotify();
    }


}
