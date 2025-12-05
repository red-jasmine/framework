<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\UserCore\Application\Services\BaseUserApplicationService;
use Throwable;

class UserSetGroupCommandHandler extends CommandHandler
{

    public function __construct(
        protected BaseUserApplicationService $service,

        // TODO
    )
    {
    }


    /**
     * @param  UserSetGroupCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(UserSetGroupCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            if ($command->groupId) {
                $this->service->groupRepository->find(FindQuery::from(['id' => $command->groupId]));
            }

            $user = $this->service->repository->find($command->id);

            $user->setGroup($command->groupId);

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