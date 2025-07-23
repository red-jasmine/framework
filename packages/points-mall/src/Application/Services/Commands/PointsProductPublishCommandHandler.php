<?php

namespace RedJasmine\PointsMall\Application\Services\Commands;

use RedJasmine\PointsMall\Application\Services\PointsProductApplicationService;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Application\Commands\CommandHandler;

class PointsProductPublishCommandHandler extends CommandHandler
{
    public function __construct(
        protected PointsProductApplicationService $service
    ) {
    }

    public function handle(PointsProductPublishCommand $command): PointsProduct
    {
        $this->beginDatabaseTransaction();
        
        try {
            $model = $this->service->repository->find($command->getKey());
            if (!$model) {
                throw new \Exception('积分商品不存在');
            }
            
            $model->putOnSale();
            $this->service->repository->update($model);
            
            $this->commitDatabaseTransaction();
            return $model;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 