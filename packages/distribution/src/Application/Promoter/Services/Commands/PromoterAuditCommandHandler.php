<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterAuditCommandHandler extends CommandHandler
{
    public function __construct(protected ApplicationService $service)
    {
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterAuditCommand $command): Promoter
    {
        $this->beginDatabaseTransaction();
        try {
            /** @var Promoter $model */
            $model = $this->service->repository->find($command->id);
            $model->setPromoterInfo($model->name, $command->remarks);
            if ($command->approved) {
                $model->enable();
            } else {
                $model->disable();
            }

            $this->service->repository->update($model);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $model;
    }
} 