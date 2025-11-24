<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class StockSubCommandHandler extends StockCommandHandler
{

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(StockCommand $command) : bool
    {

        $command->actionType = ProductStockActionTypeEnum::SUB;
        $this->context->setCommand($command);
        $this->validate($command);

        $this->beginDatabaseTransaction();

        try {
            $sku = $this->repository->add($command->variantId, $command->warehouseId, $command->actionStock);

            $this->addLog($sku, $command);

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
