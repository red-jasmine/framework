<?php

namespace RedJasmine\Region\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Region\Infrastructure\Repositories\CountryRepository;
use RedJasmine\Region\Infrastructure\Repositories\RegionRepository;

/**
 * 地区应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class RegionApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
    }
}
