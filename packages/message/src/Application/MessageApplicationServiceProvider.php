<?php

namespace RedJasmine\Message\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Message\Domain\Repositories\MessageCategoryReadRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageReadRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Infrastructure\ReadRepositories\Mysql\MessageCategoryReadRepository;
use RedJasmine\Message\Infrastructure\ReadRepositories\Mysql\MessageReadRepository;
use RedJasmine\Message\Infrastructure\Repositories\Eloquent\MessageCategoryRepository;
use RedJasmine\Message\Infrastructure\Repositories\Eloquent\MessageRepository;

class MessageApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(MessageReadRepositoryInterface::class, MessageReadRepository::class);

        $this->app->bind(MessageCategoryRepositoryInterface::class, MessageCategoryRepository::class);

        $this->app->bind(MessageCategoryReadRepositoryInterface::class, MessageCategoryReadRepository::class);
    }

    public function boot() : void
    {
    }
}
