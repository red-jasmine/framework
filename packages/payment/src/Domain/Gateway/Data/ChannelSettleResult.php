<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

class ChannelSettleResult extends AbstractChannelResult
{
    /**
     * 渠道结算单号
     * @var string
     */
    public string $channelSettleNo;

}
