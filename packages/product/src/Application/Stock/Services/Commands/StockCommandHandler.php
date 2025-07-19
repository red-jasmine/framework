<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Application\Stock\Services\CommandHandlers\Exception;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;

abstract class StockCommandHandler extends CommandHandler
{

    public function __construct(
        protected ProductSkuRepositoryInterface $repository,
        protected StockDomainService $domainService
    ) {

    }


    /**
     * @param  StockCommand  $command
     *
     * @return void
     * @throws StockException
     */
    protected function validate(StockCommand $command) : void
    {

        $this->validateQuantity($command->actionStock);

    }

    /**
     * 验证库存
     *
     * @param  int  $quantity
     *
     * @return int
     * @throws StockException
     */
    public function validateQuantity(int $quantity) : int
    {
        // 核心操作 $quantity 都为 正整数
        if (bccomp($quantity, 0, 0) < 0) {
            throw new StockException('操作库存 数量必须大于 0');
        }
        return $quantity;
    }

    /**
     * 记录
     *
     * @param  ProductSku  $sku
     * @param  StockCommand  $command
     * @param  int|null  $restStock
     *
     * @return void
     * @throws Exception
     */
    protected function log(
        ProductSku $sku,
        StockCommand $command,
        ?int $restStock = 0
    ) : void {

        $log                = new ProductStockLog;
        $log->owner         = $sku->owner;
        $log->product_id    = $command->productId;
        $log->sku_id        = $command->skuId;
        $log->change_type   = $command->changeType;
        $log->change_detail = $command->changeDetail;
        //$log->channel_type  = $command->channelType;
        //$log->channel_id    = $command->channelId;
        $log->action_type = $command->actionType;
        $log->creator     = ServiceContext::getOperator();

        $log->after_lock_stock  = $sku->lock_stock;
        $log->before_lock_stock = $sku->getOldLockStock();
        $log->after_stock       = $sku->stock;
        $log->before_stock      = $sku->getOldStock();

        switch ($command->actionType) {
            case ProductStockActionTypeEnum::ADD:
                $log->action_stock = $command->actionStock;
                $log->lock_stock   = 0;
                break;
            case ProductStockActionTypeEnum::RESET:
                $log->action_stock = $restStock;
                $log->lock_stock   = 0;
                break;
            case ProductStockActionTypeEnum::SUB:
                $log->action_stock = -$command->actionStock;
                $log->lock_stock   = 0;
                break;
            case ProductStockActionTypeEnum::LOCK:
                $log->action_stock = -$command->actionStock;
                $log->lock_stock   = $command->actionStock;
                break;
            case ProductStockActionTypeEnum::UNLOCK:
                $log->action_stock = $command->actionStock;
                $log->lock_stock   = -$command->actionStock;
                break;
            case ProductStockActionTypeEnum::CONFIRM:
                $log->action_stock = 0;
                $log->lock_stock   = -$command->actionStock;
        }

        $hasLog = true;
        if ($command->actionType === ProductStockActionTypeEnum::RESET && $restStock === 0) {
            $hasLog = false;
        }
        if ($hasLog) {
            $this->repository->log($log);
        }

    }


}
