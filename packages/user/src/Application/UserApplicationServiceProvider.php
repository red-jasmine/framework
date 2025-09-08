<?php

namespace RedJasmine\User\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserGroupRepository;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;
use RedJasmine\User\Infrastructure\Repositories\UserTagRepository;

class UserApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserTagRepositoryInterface::class, UserTagRepository::class);
        $this->app->bind(UserGroupRepositoryInterface::class, UserGroupRepository::class);
    }

    public function boot() : void
    {
    }
}
