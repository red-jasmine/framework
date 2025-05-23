<?php

namespace RedJasmine\User\Application\Services\Commands;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Application\Services\UserApplicationService;
use Throwable;

class UserSetTagsCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,
    ) {
    }


    public function handle(UserSetTagsCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $user = $this->service->repository->find($command->id);
            $user->setRelation('tags', collect($command->tags));
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