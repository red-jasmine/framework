<?php

namespace RedJasmine\Community\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Community\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Community\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Community\Infrastructure\Repositories\TopicCategoryRepository;
use RedJasmine\Community\Infrastructure\Repositories\TopicRepository;
use RedJasmine\Community\Infrastructure\Repositories\TopicTagRepository;

/**
 * 社区应用服务提供者
 *
 * 使用统一的仓库接口，支持读写操作
 */
class CommunityApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(TopicRepositoryInterface::class, TopicRepository::class);
        $this->app->bind(TopicCategoryRepositoryInterface::class, TopicCategoryRepository::class);
        $this->app->bind(TopicTagRepositoryInterface::class, TopicTagRepository::class);
    }

    public function boot() : void
    {
    }
}
