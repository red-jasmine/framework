<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Login;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserBindCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Data\UserData;
use RedJasmine\UserCore\Domain\Exceptions\UserNotFoundException;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\UserCore\Domain\Services\Login\UserLoginService;
use RedJasmine\UserCore\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserLoginOrRegisterCommandHandler extends CommandHandler
{
    public UserLoginService    $loginService;
    public UserRegisterService $userRegisterService;

    public function __construct(
        public BaseUserApplicationService $service,
    ) {
        $this->userRegisterService = new UserRegisterService(
            $this->service->repository,
            $this->service->getGuard(),
            $this->service->newModel()
        );
        $this->loginService        = new UserLoginService(
            $this->service->repository,
            $this->service->getGuard(),
        );
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
                $userRegisterCommand = new UserData();
                $user                = $this->userRegisterService->makeUser($userRegisterCommand);

                $this->service->repository->store($user);

                // 绑定社交账号
                $socialiteUserBindCommand           = new SocialiteUserBindCommand();
                $socialiteUserBindCommand->owner    = $user;
                $socialiteUserBindCommand->provider = $socialiteUser->provider;
                $socialiteUserBindCommand->clientId = $socialiteUser->client_id;
                $socialiteUserBindCommand->identity = $socialiteUser->identity;
                $socialiteUserBindCommand->appId    = 'UserCenter';

                app(SocialiteUserApplicationService::class)->bind($socialiteUserBindCommand);

                $userTokenData = $this->loginService->token($user);

            }
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $userTokenData;

    }

}
