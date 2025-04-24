<?php

namespace RedJasmine\User\Application\Services\Commands;

use Doctrine\DBAL\Driver\AbstractException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Services\ForgotPassword\ForgotPasswordService;
use Throwable;

class ForgotPasswordCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public ForgotPasswordService $forgotPassword,
    ) {
    }

    /**
     * @param  ForgotPasswordCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ForgotPasswordCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $user = $this->forgotPassword->resetPassword($command);

            $this->service->repository->update($user);

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
