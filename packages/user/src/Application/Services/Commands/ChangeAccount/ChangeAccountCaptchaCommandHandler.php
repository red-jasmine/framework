<?php

namespace RedJasmine\User\Application\Services\Commands\ChangeAccount;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCaptchaCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Services\ChangeAccount\UserChangeAccountService;
use RedJasmine\User\Domain\Services\ForgotPassword\ForgotPasswordService;
use Throwable;

class ChangeAccountCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public UserApplicationService $service,
        public UserChangeAccountService $changeAccountService,
    ) {

        $this->context = new HandleContext();
    }

    /**
     * @param  ChangeAccountCaptchaCommand  $command
     *
     * @return bool
     * @throws Throwable
     */
    public function handle(ChangeAccountCaptchaCommand $command) : bool
    {
        $this->context->setCommand($command);

        $this->beginDatabaseTransaction();

        try {
            $user = $this->service->repository->find($command->getKey());

            $this->context->setModel($user);

            $this->changeAccountService->captcha($user, $command);

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
