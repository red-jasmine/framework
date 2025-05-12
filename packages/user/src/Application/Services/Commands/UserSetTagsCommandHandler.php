<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserApplicationService;
use Throwable;

class UserSetTagsCommandHandler extends CommandHandler
{

    public function __construct(
        protected UserApplicationService $service,
    ) {
    }


    public function handle(UserSetTagsCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);

            $user->tags = $command->tags;

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