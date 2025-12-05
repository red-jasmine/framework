<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Register;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\Login\UserLoginService;
use RedJasmine\UserCore\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserRegisterCaptchaCommandHandler extends CommandHandler
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

    /**
     * @param  UserRegisterCaptchaCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(UserRegisterCaptchaCommand $command) : bool
    {


        $this->beginDatabaseTransaction();

        try {
            $this->userRegisterService->captcha($command);

            $this->commitDatabaseTransaction();

        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;


    }

}
