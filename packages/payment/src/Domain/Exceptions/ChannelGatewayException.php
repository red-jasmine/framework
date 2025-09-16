<?php

namespace RedJasmine\Payment\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class ChannelGatewayException extends AbstractException
{
    // 渠道网关异常
    public const  CHANNEL_REQUEST_ERROR = 620101; // 渠道路由


}
