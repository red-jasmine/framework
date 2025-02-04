<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class SocialiteLoginServiceProvider implements UserLoginServiceProviderInterface
{


    public const NAME = 'socialite';

    public function login(UserLoginData $data) : User
    {
        // 验证参数 TODO
        $data->data;
        $data->data['appId'] = 'UserCenter';

        $command = SocialiteUserLoginCommand::from($data->data);

        $socialiteUser = app(SocialiteUserCommandService::class)->login($command);
        dd($command);
        // 根据社交账号绑定的信息 查询用户信息
        $user = app(UserRepositoryInterface::class)->find($socialiteUser->owner_id);

        // 返回用户信息
        return $user;
    }


}
