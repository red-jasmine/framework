<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class PromoterSetParentCommandHandler extends CommandHandler
{
    public function __construct(protected ApplicationService $service)
    {
    }

    /**
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(PromoterSetParentCommand $command): Promoter
    {
        $this->beginDatabaseTransaction();
        try {
            /** @var Promoter $model */
            $model = $this->service->repository->find($command->id);
            $model->setParent($command->parentId)
                ->setPromoterInfo($model->name, $command->remarks);

            $this->service->repository->update($model);

            $this->commitDatabaseTransaction();
        } catch (BaseException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $model;
    }
} 