<?php

namespace RedJasmine\Article\Application\Services\Article\Commands;

use RedJasmine\Community\Application\Services\Topic\TopicApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class TopicPublishCommandHandler extends CommandHandler
{


    public function __construct(
        protected TopicApplicationService $service
    ) {
    }

    /**
     * @param  TopicPublishCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(TopicPublishCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $model = $this->service->repository->find($command->getKey());

            $model->publish();

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