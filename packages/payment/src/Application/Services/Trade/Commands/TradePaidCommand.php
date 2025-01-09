<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Domain\Data\ChannelTradeData;

class TradePaidCommand extends ChannelTradeData
{
    public int     $merchantAppId;

}
