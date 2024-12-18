<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\Notify\ChannelNotifyTradeCommandHandler;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @see ChannelNotifyTradeCommandHandler::handle()
 * @method tradeNotify(ChannelNotifyTradeCommand $command)
 */
class ChannelNotifyCommandService extends Service
{

    protected static $macros = [
        'tradeNotify' => ChannelNotifyTradeCommandHandler::class,
    ];


}
