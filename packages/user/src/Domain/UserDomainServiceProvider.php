<?php

namespace RedJasmine\User\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\UserCore\Domain\Services\Login\Providers\UserLoginServiceProviderManager;
use function config;

class UserDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->singleton(UserLoginServiceProviderManager::class, function () {
            $config = config('red-jasmine-user.services.login', []);
            return new UserLoginServiceProviderManager($config);
        });

    }

    public function boot() : void
    {
    }
}
