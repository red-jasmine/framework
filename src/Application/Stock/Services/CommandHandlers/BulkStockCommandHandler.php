<?php

namespace RedJasmine\Product\Application\Stock\Services\CommandHandlers;

use Exception;
use RedJasmine\Product\Application\Stock\UserCases\BulkStockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
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
                $sku = $this->repository->find($stockCommand->skuId);
                switch ($stockCommand->actionType) {

                    case ProductStockActionTypeEnum::ADD:
                        if ($stockCommand->actionStock <= 0) {
                            continue 2;
                        }
                        $this->repository->add($sku,$stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::RESET:
                        $this->repository->reset($sku,$stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::SUB:
                        if ($stockCommand->actionStock <= 0) {
                            continue 2;
                        }
                        $this->repository->sub($sku,$stockCommand->actionStock);
                        break;
                    case ProductStockActionTypeEnum::LOCK:
                    case ProductStockActionTypeEnum::UNLOCK:
                    case ProductStockActionTypeEnum::CONFIRM:
                        throw new Exception('To be implemented');
                }

                $this->log($sku, $stockCommand);
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
