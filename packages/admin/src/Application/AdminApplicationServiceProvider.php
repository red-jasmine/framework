<?php

namespace RedJasmine\Admin\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\Admin\Infrastructure\Repositories\AdminGroupRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminRepository;
use RedJasmine\Admin\Infrastructure\Repositories\AdminTagRepository;

/**
 * 管理员应用服务提供者
 *
 * 负责管理员相关仓库接口的服务绑定
 */
class AdminApplicationServiceProvider extends ServiceProvider
{
    /**
     * 注册服务绑定
     */
    public function register() : void
    {
        // 管理员仓库绑定
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);

        // 管理员标签仓库绑定
        $this->app->bind(AdminTagRepositoryInterface::class, AdminTagRepository::class);

        // 管理员分组仓库绑定
        $this->app->bind(AdminGroupRepositoryInterface::class, AdminGroupRepository::class);
    }

    /**
     * 启动服务
     */
    public function boot() : void
    {
        // 可以在这里添加需要在应用启动时执行的逻辑
    }
}
