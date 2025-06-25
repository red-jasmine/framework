<?php

namespace RedJasmine\Invitation\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationCodeRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationRecordRepository;

/**
 * 邀请应用层服务提供者
 */
class InvitationApplicationServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register() : void
    {
        $this->app->bind(InvitationCodeRepositoryInterface::class, InvitationCodeRepository::class);
        $this->app->bind(InvitationCodeReadRepositoryInterface::class, InvitationCodeReadRepository::class);
        $this->app->bind(InvitationRecordRepositoryInterface::class, InvitationRecordRepository::class);
    }

    /**
     * 启动服务
     */
    public function boot() : void
    {
        //
    }
} 