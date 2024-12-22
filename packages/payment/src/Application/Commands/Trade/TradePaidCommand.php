<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Support\Data\Data;

class TradePaidCommand extends ChannelTradeData
{
    public int     $merchantAppId;

}
