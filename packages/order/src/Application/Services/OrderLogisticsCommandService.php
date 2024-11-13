<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\Logistics\LogisticsChangeStatusCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Logistics\LogisticsChangeStatusCommand;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * 物流命令服务
 * @method void changeStatus(LogisticsChangeStatusCommand $command)
 */
class OrderLogisticsCommandService extends ApplicationCommandService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'order.application.logistics.command';
    protected static     $macros         = [
        'changeStatus' => LogisticsChangeStatusCommandHandler::class
    ];
}
