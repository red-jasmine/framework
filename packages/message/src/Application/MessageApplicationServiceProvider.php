<?php

namespace RedJasmine\Message\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessagePushLogRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageTemplateRepositoryInterface;
use RedJasmine\Message\Infrastructure\Repositories\MessageCategoryRepository;
use RedJasmine\Message\Infrastructure\Repositories\MessagePushLogRepository;
use RedJasmine\Message\Infrastructure\Repositories\MessageRepository;
use RedJasmine\Message\Infrastructure\Repositories\MessageTemplateRepository;

/**
 * 消息应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class MessageApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(MessageCategoryRepositoryInterface::class, MessageCategoryRepository::class);
        $this->app->bind(MessageTemplateRepositoryInterface::class, MessageTemplateRepository::class);
        $this->app->bind(MessagePushLogRepositoryInterface::class, MessagePushLogRepository::class);
    }

    public function boot() : void
    {
    }
}
