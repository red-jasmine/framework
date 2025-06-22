<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Services\PromoterService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterUpgradeCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterUpgradeCommand $command) : Promoter
    {
        $this->beginDatabaseTransaction();
        try {
            /** @var Promoter $model */
            $model = $this->service->repository->find($command->id);

            $promoterService = new PromoterService($this->service->levelReadRepository);

            $promoterService->apply($model, $command);

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