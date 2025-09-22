<?php

namespace RedJasmine\Project;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Project\Domain\Contracts\ProjectPermissionProviderInterface;
use RedJasmine\Project\Domain\Repositories\ProjectMemberRepositoryInterface;
use RedJasmine\Project\Domain\Repositories\ProjectRepositoryInterface;
use RedJasmine\Project\Domain\Repositories\ProjectRoleRepositoryInterface;
use RedJasmine\Project\Infrastructure\Providers\ProjectEventServiceProvider;
use RedJasmine\Project\Infrastructure\Repositories\ProjectMemberRepository;
use RedJasmine\Project\Infrastructure\Repositories\ProjectRepository;
use RedJasmine\Project\Infrastructure\Repositories\ProjectRoleRepository;
use RedJasmine\Project\Infrastructure\Services\ProjectPermissionProvider;

class ProjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/project.php', 'project');

        // 注册仓库实现
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectMemberRepositoryInterface::class, ProjectMemberRepository::class);
        $this->app->bind(ProjectRoleRepositoryInterface::class, ProjectRoleRepository::class);

        // 注册服务实现
        $this->app->bind(ProjectPermissionProviderInterface::class, ProjectPermissionProvider::class);

        // 注册事件服务提供者
        $this->app->register(ProjectEventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../config/project.php' => config_path('project.php'),
        ], 'project-config');

        // 发布数据库迁移
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'project-migrations');

        // 发布语言文件
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/project'),
        ], 'project-lang');

        // 加载数据库迁移
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // 加载语言文件
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'project');
    }
}
