<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Stock\UserCases\StockInitCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;

/**
 * @method  ProductCommandService getService()
 */
class ProductCreateCommandHandler extends ProductCommand
{


    // 需要组合 品牌服务、分类服务、卖家分类服务、属性服务

    public function handle(ProductCreateCommand $command) : Product
    {

        DB::beginTransaction();
        try {

            /**
             * @var $product Product
             */
            $product = $this->getService()->newModel($command);

            $this->handleCore($product, $command);
            $this->execute(
                execute: null,
                persistence: fn() => $this->getService()->getRepository()->store($product)
            );

            // 设置库存
            $this->handleStock($product, $command);

            DB::commit();

            return $product;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

    }


}
