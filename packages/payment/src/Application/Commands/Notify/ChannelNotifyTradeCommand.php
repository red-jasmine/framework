<?php

namespace RedJasmine\Payment\Application\Commands\Notify;

use RedJasmine\Support\Data\Data;

class ChannelNotifyTradeCommand extends Data
{


    public int $appId;

    public string $channelCode;

    public array $content;

}
