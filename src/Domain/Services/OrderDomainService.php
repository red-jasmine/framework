<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\Data\OrderData;
use RedJasmine\Shopping\Domain\Exceptions\ShoppingException;
use RedJasmine\Support\Foundation\Service\Service;

class OrderDomainService extends Service
{


    public function __construct(
        protected ProductCommandService $productCommandService,
        protected ProductQueryService   $productQueryService,
        protected StockQueryService     $stockQueryService,
        protected StockCommandService   $stockCommandService,
        protected OrderCommandService   $orderCommandService,
    )
    {

    }

    /**
     * 商品校验
     *
     * @param OrderData $orderData
     *
     * @return void
     * @throws ShoppingException
     */
    public function product(OrderData $orderData)
    {

        // 验证商品
        $productIdList = $orderData->products->pluck('productId')->unique()->toArray();
        $products      = $this->productQueryService->getRepository()->findList($productIdList);

        if (count($productIdList) !== count($products)) {
            throw  ShoppingException::newFromCodes(ShoppingException::PRODUCT_ERROR);
        }
        foreach ($products as $product) {
            if (!$product->isAllowSale()) {
                throw  ShoppingException::newFromCodes(ShoppingException::PRODUCT_OFF_SHELF);
            }
        }

        $skuList = $orderData->products->pluck('skuId')->unique()->toArray();

        // 验证库存
        $skus = $this->stockQueryService->getRepository()->findList($skuList);

        foreach ($skus as $sku) {
            // 库存是否充足、
            dd($sku);
            // SKU 是否可销售状态
        }
        // 验证状态

        // 订单分组 TODO
    }

    protected function calculation()
    {

    }


    protected function check()
    {

    }


}
