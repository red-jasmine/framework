<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\Contracts\ProviderInterface;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\CommandHandler;

class SocialiteUserLoginCommandHandler extends CommandHandler
{

    public function __construct(
        protected SocialiteUserCommandService $service,

    ) {
    }

    /**
     * 返回绑定信息
     *
     * @param  SocialiteUserLoginCommand  $command
     *
     * @return SocialiteUser
     */
    public function handle(SocialiteUserLoginCommand $command) : SocialiteUser
    {
        /**
         * @var ProviderInterface $provider
         */

        // 根据
        $command->provider;
        $command->appId;
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
        return $this->service->readRepository->findUser($query)
               ?? SocialiteUser::make([
                'provider'  => $command->provider,
                'client_id' => $command->clientId,
                'identity'  => $user->getId(),
                'app_id'    => $command->appId,
            ]);
    }


}
