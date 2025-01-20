<?php

namespace RedJasmine\Socialite\Application\Services\Login;

use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\Contracts\ProviderInterface;
use RedJasmine\Socialite\Application\Services\Login\Commands\SocialiteUserLoginCommand;

class SocialiteUserLoginCommandService
{
    public function __construct()
    {
    }


    public function login(SocialiteUserLoginCommand $command)
    {

        /**
         * @var ProviderInterface $provider
         */

        // 创建驱动
        $provider = Socialite::create('qq');
        // 获取用户信息
        $user = $provider->userFromCode($command->code);

        // 返回当前用户信息


    }

}
