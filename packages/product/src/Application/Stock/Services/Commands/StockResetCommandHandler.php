<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class StockResetCommandHandler extends StockCommandHandler
{

    /**
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(StockSetCommand $command) : void
    {
        $command->actionType = ProductStockActionTypeEnum::RESET;
        $this->getContext()->setCommand($command);

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
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}
