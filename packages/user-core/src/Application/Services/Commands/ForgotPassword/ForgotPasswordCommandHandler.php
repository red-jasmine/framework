<?php

namespace RedJasmine\UserCore\Application\Services\Commands\ForgotPassword;


use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\ForgotPasswordService;
use Throwable;

class ForgotPasswordCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
        public ForgotPasswordService $forgotPassword,
    ) {
    }

    /**
     * @param  ForgotPasswordCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(ForgotPasswordCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $user = $this->forgotPassword->resetPassword($command);

            $this->service->repository->update($user);

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
