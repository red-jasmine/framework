<?php

namespace RedJasmine\Vip\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ReadRepositories\UserVipOrderReadRepository;
use RedJasmine\Vip\Infrastructure\ReadRepositories\UserVipReadRepository;
use RedJasmine\Vip\Infrastructure\ReadRepositories\VipProductReadRepository;
use RedJasmine\Vip\Infrastructure\ReadRepositories\VipReadRepository;
use RedJasmine\Vip\Infrastructure\Repositories\UserVipOrderRepository;
use RedJasmine\Vip\Infrastructure\Repositories\UserVipRepository;
use RedJasmine\Vip\Infrastructure\Repositories\VipProductRepository;
use RedJasmine\Vip\Infrastructure\Repositories\VipRepository;

class VipApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(VipRepositoryInterface::class, VipRepository::class);
        $this->app->bind(VipReadRepositoryInterface::class, VipReadRepository::class);

        $this->app->bind(UserVipOrderRepositoryInterface::class, UserVipOrderRepository::class);
        $this->app->bind(UserVipOrderReadRepositoryInterface::class, UserVipOrderReadRepository::class);

        $this->app->bind(UserVipRepositoryInterface::class, UserVipRepository::class);
        $this->app->bind(UserVipReadRepositoryInterface::class, UserVipReadRepository::class);

        $this->app->bind(VipProductRepositoryInterface::class, VipProductRepository::class);
        $this->app->bind(VipProductReadRepositoryInterface::class, VipProductReadRepository::class);

    }

    public function boot() : void
    {
    }
}
