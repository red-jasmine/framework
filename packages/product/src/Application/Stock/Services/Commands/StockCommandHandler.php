<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Product\Application\Stock\Services\CommandHandlers\Exception;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;

abstract class StockCommandHandler extends CommandHandler
{

    public function __construct(

        protected StockApplicationService $service,

    ) {
        $this->repository = $this->service->repository;
    }

    /**
     * 添加库存记录
     *
     * @param  ProductStock  $productStock
     * @param  StockCommand  $command
     *
     * @return void
     */
    public function addLog(ProductStock $productStock, StockCommand $command) : void
    {
        $log                  = new ProductStockLog;
        $log->owner           = $productStock->owner;
        $log->product_id      = $productStock->product_id;
        $log->variant_id      = $productStock->variant_id;
        $log->warehouse_id    = $productStock->warehouse_id;
        $log->business_type   = $command->businessType;
        $log->business_no     = $command->businessNo;
        $log->business_detail = $command->businessNo;


        $log->action_type  = $command->actionType;
        $log->action_stock = $command->actionStock;
        $log->creator      = ServiceContext::getOperator();


        $log->after_stock           = $productStock->stock;
        $log->after_available_stock = $productStock->available_stock;
        $log->after_locked_stock    = $productStock->locked_stock;
        $log->after_reserved_stock  = $productStock->reserved_stock;


        $log->before_stock           = $productStock->getOriginal('stock');
        $log->before_available_stock = $productStock->getOriginal('available_stock');
        $log->before_locked_stock    = $productStock->getOriginal('locked_stock');
        $log->before_reserved_stock  = $productStock->getOriginal('reserved_stock');

        $this->service->logRepository->store($log);


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


}
