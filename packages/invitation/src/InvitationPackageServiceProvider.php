<?php

declare(strict_types=1);

namespace RedJasmine\Invitation;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Invitation\Application\InvitationApplicationServiceProvider;
use RedJasmine\Invitation\Domain\InvitationDomainServiceProvider;

/**
 * 邀请包服务提供者
 */
final class InvitationPackageServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register(): void
    {
        // 注册配置
        $this->mergeConfigFrom(
            __DIR__ . '/../config/invitation.php',
            'invitation'
        );

        // 注册其他服务提供者
        $this->app->register(InvitationDomainServiceProvider::class);
        $this->app->register(InvitationApplicationServiceProvider::class);
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 发布配置
        $this->publishes([
            __DIR__ . '/../config/invitation.php' => config_path('invitation.php'),
        ], 'invitation-config');

        // 发布数据库迁移
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'invitation-migrations');

        // 加载数据库迁移
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
} 