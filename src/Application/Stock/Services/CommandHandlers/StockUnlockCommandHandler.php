<?php

namespace RedJasmine\Product\Application\Stock\Services\CommandHandlers;

use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
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

        $this->setCommand($command);

        $this->validate($command);
        $this->beginDatabaseTransaction();

        try {
            $sku = $this->repository->find($command->skuId);

            $this->repository->unlock($sku, $command->stock);

            $this->log($sku, ProductStockTypeEnum::UNLOCK, $command);

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
