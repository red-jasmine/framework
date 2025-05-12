<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;
use Throwable;

class UserSetGroupCommandHandler extends CommandHandler
{

    public function __construct(
        protected UserApplicationService $service,
        protected UserGroupReadRepositoryInterface $groupReadRepository,
    ) {
    }


    /**
     * @param  UserSetGroupCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UserSetGroupCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            if ($command->groupId) {
                $this->groupReadRepository->find(FindQuery::from(['id' => $command->groupId]));
            }

            $user = $this->service->repository->find($command->id);

            $user->setGroup($command->groupId);

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