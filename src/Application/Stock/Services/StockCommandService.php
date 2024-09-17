<?php

namespace RedJasmine\Product\Application\Stock\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuRepositoryInterface;
use RedJasmine\Product\Domain\Stock\StockDomainService;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Facades\ServiceContext;
use Throwable;

/**
 * TODO 需要改造
 */
class StockCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix  = 'product.application.stock.command';

    public function __construct(
        protected ProductSkuRepositoryInterface $repository,
        protected StockDomainService            $domainService
    )
    {

    }


    protected static $macros = [];

    /**
     * 验证库存
     *
     * @param int $quantity
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
     * 重置库存
     *
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     * @throws StockException
     */
    public function set(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $stock          = $this->repository->reset($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::SET, $command, $stock);

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }

    /**
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     */
    public function add(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $this->repository->add($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::ADD, $command);
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }

    /**
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     */
    public function sub(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $this->repository->sub($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::SUB, $command);
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }

    /**
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     */
    public function lock(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $this->repository->lock($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::LOCK, $command);
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }

    /**
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     */
    public function unlock(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $this->repository->unlock($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::UNLOCK, $command);
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }

    /**
     * @param StockCommand $command
     *
     * @return void
     * @throws Throwable
     */
    public function confirm(StockCommand $command) : void
    {
        try {
            DB::beginTransaction();
            $command->stock = $this->validateQuantity($command->stock);
            $sku            = $this->repository->find($command->skuId);
            $this->repository->confirm($sku, $command->stock);
            $this->log($sku, ProductStockTypeEnum::CONFIRM, $command);
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
    }


    /**
     * 重置库存
     *
     * @param ProductSku           $sku
     * @param ProductStockTypeEnum $stockType
     * @param StockCommand         $command
     * @param int|null             $restStock
     *
     * @return void
     * @throws Exception
     */
    protected function log(ProductSku $sku, ProductStockTypeEnum $stockType, StockCommand $command, ?int $restStock = 0) : void
    {

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
