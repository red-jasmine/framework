<?php

namespace RedJasmine\Article\Application\Services\Article\Commands;

use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ArticlePublishCommandHandler extends CommandHandler
{


    public function __construct(
        protected ArticleApplicationService $service
    ) {
    }

    /**
     * @param  ArticlePublishCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ArticlePublishCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $model = $this->service->repository->find($command->getKey());

            $model->publish();

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