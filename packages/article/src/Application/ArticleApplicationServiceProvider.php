<?php

namespace RedJasmine\Article\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Article\Infrastructure\ReadRepositories\Mysql\ArticleCategoryReadRepository;
use RedJasmine\Article\Infrastructure\ReadRepositories\Mysql\ArticleReadRepository;
use RedJasmine\Article\Infrastructure\Repositories\Eloquent\ArticleCategoryRepository;
use RedJasmine\Article\Infrastructure\Repositories\Eloquent\ArticleRepository;

class ArticleApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(ArticleReadRepositoryInterface::class, ArticleReadRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        $this->app->bind(ArticleCategoryReadRepositoryInterface::class, ArticleCategoryReadRepository::class);
        $this->app->bind(ArticleCategoryRepositoryInterface::class, ArticleCategoryRepository::class);

    }

    public function boot() : void
    {
    }
}
