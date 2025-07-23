<?php

namespace RedJasmine\PointsMall\Application\Services\Commands;

use RedJasmine\PointsMall\Application\Services\PointsProductApplicationService;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Application\Commands\CommandHandler;

class PointsProductOffSaleCommandHandler extends CommandHandler
{
    public function __construct(
        protected PointsProductApplicationService $service
    ) {
    }

    public function handle(PointsProductOffSaleCommand $command): PointsProduct
    {
        $this->beginDatabaseTransaction();
        
        try {
            $model = $this->service->repository->find($command->id);
            if (!$model) {
                throw new \Exception('积分商品不存在');
            }
            
            $model->putOffSale();
            $this->service->repository->update($model);
            
            $this->commitDatabaseTransaction();
            return $model;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 