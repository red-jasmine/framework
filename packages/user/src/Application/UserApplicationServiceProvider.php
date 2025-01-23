<?php

namespace RedJasmine\User\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserReadRepository;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;

class UserApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(UserReadRepositoryInterface::class, UserReadRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);


    }

    public function boot() : void
    {
    }
}
