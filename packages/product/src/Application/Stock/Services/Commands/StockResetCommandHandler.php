<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class StockResetCommandHandler extends StockCommandHandler
{

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(StockCommand $command) : void
    {
        $command->actionType = ProductStockActionTypeEnum::RESET;
        $this->setCommand($command);

        $this->validate($command);
        $this->beginDatabaseTransaction();

        try {
            $sku   = $this->repository->find($command->skuId);
            $stock = $this->repository->reset($sku, $command->actionStock);

            $this->log($sku,  $command, $stock);

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
