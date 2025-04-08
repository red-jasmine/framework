<?php

namespace RedJasmine\Support\Application\Commands;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class SubmitApprovalCommandHandler extends CommandHandler
{


    public function __construct(
        protected ApplicationService $service
    ) {
    }

    /**
     * @param  SubmitApprovalCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(SubmitApprovalCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->getKey());

            $model->submitApproval($command);

            $this->service->repository->update($model);

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