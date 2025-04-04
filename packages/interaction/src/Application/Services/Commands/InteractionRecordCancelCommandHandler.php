<?php

namespace RedJasmine\Interaction\Application\Services\Commands;

use RedJasmine\Interaction\Application\Services\InteractionRecordApplicationService;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Facades\InteractionResource;
use RedJasmine\Interaction\Domain\Facades\InteractionType;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Services\InteractionDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class InteractionRecordCancelCommandHandler extends CommandHandler
{

    public function __construct(
        protected InteractionRecordApplicationService $service
    ) {
    }


    /**
     * @param  InteractionData  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(InteractionData $command) : bool
    {

        $resource        = InteractionResource::create($command->resourceType);
        $interactionType = InteractionType::create($command->interactionType);


        // 查询互动记录

        $record = $this->service->repository->findByInteractionType($command->interactionType, $command->getKey());

        $this->beginDatabaseTransaction();
        try {
            $interactionDomainService = new InteractionDomainService(
                $resource,
                $interactionType,
                $this->service->statisticRepository,
            );

            $interactionDomainService->cancel($record);


            $this->service->repository->delete($record);

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