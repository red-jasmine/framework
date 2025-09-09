<?php

namespace RedJasmine\Logistics\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateRepositoryInterface;
use RedJasmine\Logistics\Infrastructure\Repositories\LogisticsCompanyRepository;
use RedJasmine\Logistics\Infrastructure\Repositories\LogisticsFreightTemplateRepository;

/**
 * 物流应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class LogisticsApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(LogisticsFreightTemplateRepositoryInterface::class, LogisticsFreightTemplateRepository::class);
        $this->app->bind(LogisticsCompanyRepositoryInterface::class, LogisticsCompanyRepository::class);
    }
}
