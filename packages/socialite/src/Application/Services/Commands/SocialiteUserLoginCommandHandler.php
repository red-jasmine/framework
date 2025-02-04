<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\Contracts\ProviderInterface;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

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
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(SocialiteUserLoginCommand $command) : SocialiteUser
    {

        $this->beginDatabaseTransaction();

        try {
            /**
             * @var ProviderInterface $provider
             */
            $provider = Socialite::create($command->provider);
            // 获取用户信息
            $user = $provider->userFromCode($command->code);
            // 查询绑定用户信息

            $query         = SocialiteUserFindUserQuery::from([
                'provider'  => $command->provider,
                'client_id' => $command->clientId,
                'identity'  => $user->getId(),
                'app_id'    => $command->appId,

            ]);
            $socialiteUser = $this->service->readRepository->findUser($query);

            if (!$socialiteUser) {
                $socialiteUser = SocialiteUser::make([
                    'provider'  => $command->provider,
                    'client_id' => $command->clientId,
                    'identity'  => $user->getId(),
                    'app_id'    => $command->appId,
                ]);

                $this->service->repository->store($socialiteUser);
            }

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $socialiteUser;

    }


}
