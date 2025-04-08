<?php

namespace RedJasmine\Community\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Community\Domain\Repositories\TopicTagReadRepositoryInterface;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Community\Infrastructure\ReadRepositories\Mysql\TopicCategoryReadRepository;
use RedJasmine\Community\Infrastructure\ReadRepositories\Mysql\TopicReadRepository;
use RedJasmine\Community\Infrastructure\ReadRepositories\Mysql\TopicTagReadRepository;
use RedJasmine\Community\Infrastructure\Repositories\Eloquent\TopicCategoryRepository;
use RedJasmine\Community\Infrastructure\Repositories\Eloquent\TopicRepository;
use RedJasmine\Community\Infrastructure\Repositories\Eloquent\TopicTagRepository;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryReadRepositoryInterface;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Comnunity\Domain\Repositories\TopicReadRepositoryInterface;
use RedJasmine\Comnunity\Domain\Repositories\TopicRepositoryInterface;

class CommunityApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(TopicReadRepositoryInterface::class, TopicReadRepository::class);
        $this->app->bind(TopicRepositoryInterface::class, TopicRepository::class);


        $this->app->bind(TopicCategoryReadRepositoryInterface::class, TopicCategoryReadRepository::class);
        $this->app->bind(TopicCategoryRepositoryInterface::class, TopicCategoryRepository::class);


        $this->app->bind(TopicTagReadRepositoryInterface::class, TopicTagReadRepository::class);
        $this->app->bind(TopicTagRepositoryInterface::class, TopicTagRepository::class);
    }

    public function boot() : void
    {
    }
}
