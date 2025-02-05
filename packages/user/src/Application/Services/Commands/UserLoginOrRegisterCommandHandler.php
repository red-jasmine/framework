<?php

namespace RedJasmine\User\Application\Services\Commands;

use Illuminate\Support\Str;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserBindCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserCommandService;
use RedJasmine\User\Domain\Exceptions\UserNotFoundException;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\UserLoginService;
use RedJasmine\User\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserLoginOrRegisterCommandHandler extends CommandHandler
{
    public function __construct(
        public UserCommandService $service,
        public UserLoginService $loginService,
        public UserRegisterService $userRegisterService
    ) {
    }

    public function handle(UserLoginOrRegisterCommand $command) : UserTokenData
    {
        $this->beginDatabaseTransaction();

        try {

            try {
                $userTokenData = $this->loginService->login($command);
            } catch (UserNotFoundException $userNotFoundException) {


                // 如果存在第三方登录信息，则创建用户
                if (!$socialiteUser = $userNotFoundException->getSocialiteUser()) {
                    throw $userNotFoundException;
                }

                // 注册用户
                $userRegisterCommand           = new UserRegisterCommand();
                $userRegisterCommand->username = Str::uuid();

                $user = $this->userRegisterService->register($userRegisterCommand);

                $this->service->repository->store($user);

                // 绑定社交账号
                $socialiteUserBindCommand           = new SocialiteUserBindCommand();
                $socialiteUserBindCommand->owner    = $user;
                $socialiteUserBindCommand->provider = $socialiteUser->provider;
                $socialiteUserBindCommand->clientId = $socialiteUser->client_id;
                $socialiteUserBindCommand->identity = $socialiteUser->identity;
                $socialiteUserBindCommand->appId    = 'UserCenter';

                app(SocialiteUserCommandService::class)->bind($socialiteUserBindCommand);
                $userTokenData = $this->loginService->token($user);

            }
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $userTokenData;

    }

}
