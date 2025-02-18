<?php

namespace RedJasmine\Vip\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ReadRepositories\UserVipReadRepository;
use RedJasmine\Vip\Infrastructure\ReadRepositories\VipReadRepository;
use RedJasmine\Vip\Infrastructure\Repositories\UserVipRepository;
use RedJasmine\Vip\Infrastructure\Repositories\VipRepository;

class VipApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(VipRepositoryInterface::class, VipRepository::class);
        $this->app->bind(VipReadRepositoryInterface::class, VipReadRepository::class);


        $this->app->bind(UserVipRepositoryInterface::class, UserVipRepository::class);
        $this->app->bind(UserVipReadRepositoryInterface::class, UserVipReadRepository::class);

    }

    public function boot() : void
    {
    }
}
