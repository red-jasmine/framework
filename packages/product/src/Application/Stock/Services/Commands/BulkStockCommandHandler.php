<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Exceptions\AbstractException;
use RuntimeException;
use Throwable;

class BulkStockCommandHandler extends StockCommandHandler
{

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(BulkStockCommand $command) : void
    {

        $this->setCommand($command);


        $this->beginDatabaseTransaction();

        try {
            foreach ($command->skus as $stockCommand) {
                $restStock = 0;
                $sku       = $this->repository->find($stockCommand->skuId);
                if ($stockCommand->actionStock < 0) {
                    throw new StockException('操作库存不能小于0');
                }
                switch ($stockCommand->actionType) {

                    case ProductStockActionTypeEnum::ADD:
                        if ($stockCommand->actionStock === 0) {
                            continue 2;
                        }
                        $this->repository->add($sku, $stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::RESET:

                        $restStock = $this->repository->reset($sku, $stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::SUB:
                        if ($stockCommand->actionStock === 0) {
                            continue 2;
                        }
                        $this->repository->sub($sku, $stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::LOCK:
                    case ProductStockActionTypeEnum::UNLOCK:
                    case ProductStockActionTypeEnum::CONFIRM:
                        throw new RuntimeException('To be implemented');
                }

                $this->log($sku, $stockCommand, $restStock);
            }
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
