<?php

namespace RedJasmine\Support\Application\Commands;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class ApprovalCommandHandler extends CommandHandler
{


    public function __construct(
        protected ApplicationService $service
    ) {
        $this->initHandleContext();
    }

    /**
     * @param  ApprovalCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(ApprovalCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->getKey());

            $model->handleApproval($command);

            $this->service->repository->update($model);

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