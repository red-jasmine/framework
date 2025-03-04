<?php

namespace RedJasmine\Payment\Application\Services\AsyncNotify\Commands;

use RedJasmine\Support\Data\Data;

class ChannelNotifyTradeCommand extends Data
{


    public int $appId;

    public string $channelCode;

    public array $content;

    public array $headers;


}
