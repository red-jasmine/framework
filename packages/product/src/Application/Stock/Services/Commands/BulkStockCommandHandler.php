<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Exceptions\BaseException;
use RuntimeException;
use Throwable;

class BulkStockCommandHandler extends StockCommandHandler
{

    /**
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(BulkStockCommand $command) : void
    {

        $this->context->setCommand($command);


        $this->beginDatabaseTransaction();

        try {
            foreach ($command->variants as $stockCommand) {

                $restStock = 0;
                $sku       = $this->repository->find($stockCommand->skuId);

                if ($stockCommand->actionStock < 0) {
                    throw new StockException('操作库存不能小于0');
                }
                switch ($stockCommand->actionType) {

                    case ProductStockActionTypeEnum::SUB:
                    case ProductStockActionTypeEnum::ADD:
                        if ($stockCommand->actionStock === 0) {
                            continue 2;
                        }
                        $sku = $this->repository->add($stockCommand->variantId, $stockCommand->warehouseId, $stockCommand->actionStock);

                        break;
                    case ProductStockActionTypeEnum::RESET:
                        $sku = $this->repository->add($stockCommand->variantId, $stockCommand->warehouseId, $stockCommand->actionStock);

                        break;
                    default:
                        throw new RuntimeException('To be implemented');
                }

                $this->addLog($sku, $stockCommand);

            }
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
