<?php

namespace RedJasmine\Interaction\Application\Services\Commands;

use RedJasmine\Interaction\Application\Services\InteractionApplicationService;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;
use RedJasmine\Interaction\Domain\Facades\InteractionType;
use RedJasmine\Interaction\Domain\Services\InteractionDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;

class InteractionCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected InteractionApplicationService $service
    ) {
    }


    /**
     * @param  InteractionCreateCommand  $command
     *
     * @return InteractionRecord
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(InteractionCreateCommand $command) : InteractionRecord
    {

        $resource        = InteractionResource::create($command->resourceType);
        $interactionType = InteractionType::create($command->interactionType);

        $this->beginDatabaseTransaction();
        try {
            $interactionDomainService = new InteractionDomainService(
                $resource,
                $interactionType,
                $this->service->repository,
            );

            $model = $interactionDomainService->interactive($command);

            $this->service->recordRepository->store($model);

            $this->service->repository->increment(
                $command->resourceType,
                $command->resourceId,
                $command->interactionType,
                $command->quantity,
            );

            $this->commitDatabaseTransaction();

        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $model;
    }

}