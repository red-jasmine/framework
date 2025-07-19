<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class StockUnlockCommandHandler extends StockCommandHandler
{

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(StockCommand $command) : void
    {
        $command->actionType = ProductStockActionTypeEnum::UNLOCK;
        $this->context->setCommand($command);

        $this->validate($command);
        $this->beginDatabaseTransaction();

        try {
            $sku = $this->repository->find($command->skuId);

            $sku = $this->repository->unlock($sku, $command->actionStock);

            $this->log($sku, $command);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}
