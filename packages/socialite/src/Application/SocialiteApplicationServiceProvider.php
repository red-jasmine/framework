<?php

namespace RedJasmine\Socialite\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Socialite\Infrastructure\ReadRepositories\SocialiteUserReadRepository;
use RedJasmine\Socialite\Infrastructure\Repositories\SocialiteUserRepository;

class SocialiteApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(SocialiteUserRepositoryInterface::class, SocialiteUserRepository::class);
        $this->app->bind(SocialiteUserReadRepositoryInterface::class, SocialiteUserReadRepository::class);

    }

    public function boot() : void
    {
    }
}
