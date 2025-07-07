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
            $sku       = $this->repository->find($command->skuId);
            $sku       = $this->repository->reset($sku, $command->actionStock);
            $restStock = (int) bcsub($sku->stock, $sku->getOldStock(), 0);
            $this->log($sku, $command, $restStock);

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
