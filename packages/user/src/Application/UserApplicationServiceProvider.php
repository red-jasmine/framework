<?php

namespace RedJasmine\User\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagCategoryReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagCategoryRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserGroupReadRepository;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserReadRepository;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserTagCategoryReadRepository;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserTagReadRepository;
use RedJasmine\User\Infrastructure\Repositories\UserGroupRepository;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;
use RedJasmine\User\Infrastructure\Repositories\UserTagCategoryRepository;
use RedJasmine\User\Infrastructure\Repositories\UserTagRepository;

class UserApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(UserReadRepositoryInterface::class, UserReadRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);


        $this->app->bind(UserTagReadRepositoryInterface::class, UserTagReadRepository::class);
        $this->app->bind(UserTagRepositoryInterface::class, UserTagRepository::class);


        $this->app->bind(UserTagCategoryReadRepositoryInterface::class, UserTagCategoryReadRepository::class);
        $this->app->bind(UserTagCategoryRepositoryInterface::class, UserTagCategoryRepository::class);


        $this->app->bind(UserGroupReadRepositoryInterface::class, UserGroupReadRepository::class);
        $this->app->bind(UserGroupRepositoryInterface::class, UserGroupRepository::class);

    }

    public function boot() : void
    {
    }
}
