<?php

namespace RedJasmine\Payment\Domain\Exceptions;

use RedJasmine\Support\Exceptions\BaseException;

class ChannelGatewayException extends BaseException
{
    // 渠道网关异常
    public const  CHANNEL_REQUEST_ERROR = 620101; // 渠道路由


}
