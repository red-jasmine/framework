<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Services\Register\UserRegisterService;
use Throwable;

class UserRegisterCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public UserRegisterService $userRegisterService
    ) {
    }

    /**
     * @param  UserRegisterCaptchaCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserRegisterCaptchaCommand $command) : bool
    {


        $this->beginDatabaseTransaction();

        try {
            $this->userRegisterService->captcha($command);

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return true;


    }

}
