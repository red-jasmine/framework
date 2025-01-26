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

    public function __construct(
        protected SocialiteUserCommandService $socialiteUserCommandService,
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public const NAME = 'socialite';

    public function login(UserLoginData $data) : User
    {
        // 验证参数

        // 根据用户信息 查询 社交账号的 绑定的信息
        // 查询配置信息 TODO
        $command           = new SocialiteUserLoginCommand();
        $command->provider = $data->data['provider'];
        $command->clientId = $data->data['client_id'];
        $command->appId    = 'UserCenter'; // TODO
        $command->code     = $data->data['code'];


        $socialiteUser = $this->socialiteUserCommandService->login($command);

        // 根据社交账号绑定的信息 查询用户信息

        $user = $this->userRepository->find($socialiteUser->owner_id);


        // 返回用户信息

        return $user;
    }


}
