<?php

namespace RedJasmine\Payment\Application\Services\AsyncNotify\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class ChannelNotifyTradeCommand extends Data
{

    public string $channelCode; // 渠道

    public int $appId; // 应用


    public array $content; // 通知内容

    public array $request; // 请求


}
