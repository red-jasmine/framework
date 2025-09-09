<?php

namespace RedJasmine\Vip\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;
use RedJasmine\Vip\Infrastructure\Repositories\UserVipOrderRepository;
use RedJasmine\Vip\Infrastructure\Repositories\UserVipRepository;
use RedJasmine\Vip\Infrastructure\Repositories\VipProductRepository;
use RedJasmine\Vip\Infrastructure\Repositories\VipRepository;

class VipApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(VipRepositoryInterface::class, VipRepository::class);
        $this->app->bind(UserVipOrderRepositoryInterface::class, UserVipOrderRepository::class);
        $this->app->bind(UserVipRepositoryInterface::class, UserVipRepository::class);
        $this->app->bind(VipProductRepositoryInterface::class, VipProductRepository::class);
    }

    public function boot() : void
    {
    }
}
