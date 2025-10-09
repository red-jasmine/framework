<?php

namespace RedJasmine\UserCore\Application\Services\Commands\ChangeAccount;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\AbstractException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\UserChangeAccountService;
use Throwable;

class ChangeAccountVerifyCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
        public UserChangeAccountService $changeAccountService,
    ) {

        $this->context = new HandleContext();
    }

    /**
     * @param  ChangeAccountVerifyCommand  $command
     *
     * @return bool
     * @throws Throwable
     */
    public function handle(ChangeAccountVerifyCommand $command) : bool
    {
        $this->context->setCommand($command);

        $this->beginDatabaseTransaction();

        try {
            $user = $this->service->repository->find($command->getKey());

            $this->context->setModel($user);

            $this->changeAccountService->verify($user, $command);

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
