<?php

namespace RedJasmine\Interaction\Application\Services\Commands;

use RedJasmine\Interaction\Domain\Facades\Interaction;
use RedJasmine\Interaction\Domain\Services\InteractionDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class InteractionCreateCommandHandler extends CommandHandler
{


    public function handle(InteractionCreateCommand $command)
    {
        // 查询或者创建资源 统计

        $strategy = Interaction::create($command->resourceType);
        $service  = new InteractionDomainService($strategy);

        $model = $service->interactive($command);

        $model['statistic'];
        $model['record'];
        // 获取 资源策略
        // 执行策略
        // 添加 资源互动统计
        // 添加 资源互动记录
        // 添加 资源互动特殊功能记录

    }

}