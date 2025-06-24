<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Invitation\Domain\Transformers\InvitationCodeTransformer;

/**
 * 邀请领域服务提供者
 */
final class InvitationDomainServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register(): void
    {
        // 注册领域转换器
        $this->app->singleton(InvitationCodeTransformer::class);
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 注册领域事件监听器
        $this->registerEventListeners();
    }

    /**
     * 注册事件监听器
     */
    protected function registerEventListeners(): void
    {
        // 可以在这里注册领域事件监听器
        // Event::listen(InvitationCodeCreated::class, InvitationCodeCreatedListener::class);
        // Event::listen(InvitationCodeUsed::class, InvitationCodeUsedListener::class);
    }
} 