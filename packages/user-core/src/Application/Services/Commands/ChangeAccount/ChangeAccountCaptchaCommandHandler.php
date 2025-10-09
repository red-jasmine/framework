<?php

namespace RedJasmine\UserCore\Application\Services\Commands\ChangeAccount;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\UserChangeAccountService;
use Throwable;

class ChangeAccountCaptchaCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
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
