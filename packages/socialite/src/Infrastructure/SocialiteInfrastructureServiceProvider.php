<?php

namespace RedJasmine\Socialite\Infrastructure;

use Illuminate\Support\ServiceProvider;
use Overtrue\LaravelSocialite\Socialite;
use RedJasmine\Socialite\Infrastructure\Providers\WechatMiniAppProvider;

class SocialiteInfrastructureServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        Socialite::extend('wechat_mini', function (array $config) {
            return new WechatMiniAppProvider($config);
        });
    }
}
