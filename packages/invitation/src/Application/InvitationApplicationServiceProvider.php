<?php

namespace RedJasmine\Invitation\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Invitation\Application\Pipelines\UserRegister\UserRegisterPipeline;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\Invitation\Infrastructure\Repositories\InvitationCodeRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\InvitationRecordRepository;
use RedJasmine\Support\Facades\Hook;

/**
 * 邀请应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class InvitationApplicationServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(InvitationCodeRepositoryInterface::class, InvitationCodeRepository::class);
        $this->app->bind(InvitationRecordRepositoryInterface::class, InvitationRecordRepository::class);
    }

    /**
     * 启动服务
     */
    public function boot() : void
    {
        Hook::register('user.register.makeUser',UserRegisterPipeline::class);
    }
}
