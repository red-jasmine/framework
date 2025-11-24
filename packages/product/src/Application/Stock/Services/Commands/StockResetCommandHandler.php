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
    public function handle(StockSetCommand $command) : void
    {
        $command->actionType = ProductStockActionTypeEnum::RESET;
        $this->context->setCommand($command);

        $this->validate($command);


        $this->beginDatabaseTransaction();

        try {

            $sku = $this->repository->reset(
                $command->variantId,
                $command->warehouseId,
                $command->actionStock,
                $command->productId,
                $command->owner->getType(),
                $command->owner->getID(),
            );

            $this->addLog($sku, $command);

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
