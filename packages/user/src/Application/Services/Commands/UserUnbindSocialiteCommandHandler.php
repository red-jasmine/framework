<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\UserSocialiteService;
use Throwable;

class UserUnbindSocialiteCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,
        protected UserSocialiteService $userSocialiteService
    ) {
    }


    public function handle(UserUnbindSocialiteCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $this->userSocialiteService->unbind($user, $command->provider);

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