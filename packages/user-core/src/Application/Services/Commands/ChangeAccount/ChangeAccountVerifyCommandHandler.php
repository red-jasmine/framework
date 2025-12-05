<?php

namespace RedJasmine\UserCore\Application\Services\Commands\ChangeAccount;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\UserChangeAccountService;
use Throwable;

class ChangeAccountVerifyCommandHandler extends CommandHandler
{
    public function __construct(
        public BaseUserApplicationService $service,
        public UserChangeAccountService $changeAccountService,
    ) {

        
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
