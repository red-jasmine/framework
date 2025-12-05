<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class StockSubCommandHandler extends StockCommandHandler
{

    /**
     * @throws BaseException
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

        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return true;

    }

}
