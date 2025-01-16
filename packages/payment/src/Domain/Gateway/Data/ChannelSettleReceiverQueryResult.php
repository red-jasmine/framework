<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Payment\Domain\Gateway\ChannelSettleReceiverData;

class ChannelSettleReceiverQueryResult extends AbstractChannelResult
{

    public int $totalCount      = 0;
    public int $totalPage       = 0;
    public int $currentPage     = 1;
    public int $currentPageSize = 20;

    /**
     * @var ChannelSettleReceiverData[]
     */
    public array $receivers = [];

}
