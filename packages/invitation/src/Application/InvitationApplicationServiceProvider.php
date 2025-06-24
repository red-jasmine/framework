<?php

namespace RedJasmine\Invitation\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Invitation\Domain\ReadRepositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationCodeRepository;

/**
 * 邀请应用层服务提供者
 */
class InvitationApplicationServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register(): void
    {
        // 绑定仓库接口和实现
        $this->app->bind(InvitationCodeReadRepositoryInterface::class, InvitationCodeReadRepository::class);
        $this->app->bind(InvitationCodeRepositoryInterface::class, InvitationCodeRepository::class);
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 应用层启动逻辑
    }
} 