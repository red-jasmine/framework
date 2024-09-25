<?php

namespace RedJasmine\Product\Application\Stock\Services\CommandHandlers;

use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class StockLockCommandHandler extends StockCommandHandler
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
            $sku   = $this->repository->find($command->skuId);

            $this->repository->lock($sku, $command->actionStock);

            $this->log($sku,  $command);

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
