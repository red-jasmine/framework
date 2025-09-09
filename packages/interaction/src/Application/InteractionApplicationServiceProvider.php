<?php

namespace RedJasmine\Interaction\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Interaction\Infrastructure\Repositories\InteractionRecordRepository;
use RedJasmine\Interaction\Infrastructure\Repositories\InteractionStatisticRepository;

/**
 * 互动应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class InteractionApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(InteractionStatisticRepositoryInterface::class, InteractionStatisticRepository::class);
        $this->app->bind(InteractionRecordRepositoryInterface::class, InteractionRecordRepository::class);
    }
}
