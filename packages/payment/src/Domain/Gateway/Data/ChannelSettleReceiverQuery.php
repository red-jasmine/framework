<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelSettleReceiverQuery extends Data
{

    public int $page = 1;

    public int $pageSize = 20;

}
