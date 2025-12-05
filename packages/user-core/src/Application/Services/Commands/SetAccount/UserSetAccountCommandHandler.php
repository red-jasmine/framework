<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetAccount;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\UserChangeAccountService;
use Throwable;

class UserSetAccountCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,

    ) {
    }


    /**
     * @param  UserSetAccountCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(UserSetAccountCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $service = new UserChangeAccountService($this->service->repository);

            $service->setAccount($user, $command);

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