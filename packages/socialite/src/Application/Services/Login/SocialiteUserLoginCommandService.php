<?php

namespace RedJasmine\Socialite\Application\Services\Login;

use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\Contracts\ProviderInterface;
use RedJasmine\Socialite\Application\Services\Login\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;

class SocialiteUserLoginCommandService
{
    public function __construct(

        protected SocialiteUserReadRepositoryInterface $readRepository
    ) {
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
        // 查询绑定用户信息

        $query = SocialiteUserFindUserQuery::from([
            'provider'  => $command->provider,
            'client_id' => $command->clientId,
            'identity'  => $user->getId(),
            'app_id'    => $command->appId,

        ]);
        return $this->readRepository->findUser($query)
               ?? SocialiteUser::make([
                'provider'  => $command->provider,
                'client_id' => $command->clientId,
                'identity'  => $user->getId(),
                'app_id'    => $command->appId,
            ]);

    }

}
