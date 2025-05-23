<?php

namespace RedJasmine\Admin\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\Admin\Domain\Repositories\AdminGroupReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminTagReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql\AdminGroupReadRepository;
use RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql\AdminReadRepository;
use RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql\AdminTagReadRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminGroupRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminTagRepository;

class AdminApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminReadRepositoryInterface::class, AdminReadRepository::class);

        $this->app->bind(AdminTagRepositoryInterface::class, AdminTagRepository::class);
        $this->app->bind(AdminTagReadRepositoryInterface::class, AdminTagReadRepository::class);

        $this->app->bind(AdminGroupRepositoryInterface::class, AdminGroupRepository::class);
        $this->app->bind(AdminGroupReadRepositoryInterface::class, AdminGroupReadRepository::class);

    }

    public function boot() : void
    {
    }
}
