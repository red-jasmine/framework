<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Support\Data\Data;

/**
 * 发起支付
 */
class TradePayingCommand extends Data
{
    public int $id;

    /**
     * 选择支付渠道
     * @var string
     */
    public string $channel;



}
