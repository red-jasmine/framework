<?php

namespace RedJasmine\Product\Application\Stock\Services\CommandHandlers;

use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
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

        $this->validateQuantity($command->stock);

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
     * @param  ProductStockTypeEnum  $stockType
     * @param  StockCommand  $command
     * @param  int|null  $restStock
     *
     * @return void
     * @throws Exception
     */
    protected function log(
        ProductSku $sku,
        ProductStockTypeEnum $stockType,
        StockCommand $command,
        ?int $restStock = 0
    ) : void {

        $log                = new ProductStockLog;
        $log->owner         = $sku->owner;
        $log->product_id    = $command->productId;
        $log->sku_id        = $command->skuId;
        $log->change_type   = $command->changeType;
        $log->change_detail = $command->changeDetail;
        $log->channel_type  = $command->channelType;
        $log->channel_id    = $command->channelId;
        $log->type          = $stockType;
        $log->creator       = ServiceContext::getOperator();

        switch ($stockType) {
            case ProductStockTypeEnum::ADD:
                $log->stock      = $command->stock;
                $log->lock_stock = 0;
                break;
            case ProductStockTypeEnum::SET:
                $log->stock      = $restStock;
                $log->lock_stock = 0;
                break;
            case ProductStockTypeEnum::SUB:
                $log->stock      = -$command->stock;
                $log->lock_stock = 0;
                break;
            case ProductStockTypeEnum::LOCK:
                $log->stock      = -$command->stock;
                $log->lock_stock = $command->stock;
                break;
            case ProductStockTypeEnum::UNLOCK:
                $log->stock      = $command->stock;
                $log->lock_stock = -$command->stock;
                break;
            case ProductStockTypeEnum::CONFIRM:
                $log->stock      = 0;
                $log->lock_stock = -$command->stock;
        }

        $hasLog = true;
        if ($stockType === ProductStockTypeEnum::SET && $restStock === 0) {
            $hasLog = false;
        }
        if ($hasLog) {
            $this->repository->log($log);
        }

    }


}
