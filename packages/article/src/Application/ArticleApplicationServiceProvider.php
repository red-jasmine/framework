<?php

namespace RedJasmine\Article\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Article\Infrastructure\Repositories\ArticleCategoryRepository;
use RedJasmine\Article\Infrastructure\Repositories\ArticleRepository;
use RedJasmine\Article\Infrastructure\Repositories\ArticleTagRepository;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;
use RedJasmine\Interaction\Domain\InteractionResourceManager;

/**
 * 文章应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class ArticleApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ArticleCategoryRepositoryInterface::class, ArticleCategoryRepository::class);
        $this->app->bind(ArticleTagRepositoryInterface::class, ArticleTagRepository::class);
    }

    public function boot() : void
    {
        // 启动逻辑
    }
}
