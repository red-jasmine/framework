<?php

namespace RedJasmine\Admin\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Admin\Domain\Repositories\AdminReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql\AdminReadRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminRepository;

class AdminApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminReadRepositoryInterface::class, AdminReadRepository::class);

    }

    public function boot() : void
    {
    }
}
