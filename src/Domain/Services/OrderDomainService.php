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
     * @return OrderData
     * @throws ShoppingException
     */
    public function product(OrderData $orderData) : OrderData
    {

        // 验证商品
        $productIdList = $orderData->products->pluck('productId')->unique()->toArray();
        $products      = $this->productQueryService->getRepository()->findList($productIdList);

        if (count($productIdList) !== count($products)) {
            throw  ShoppingException::newFromCodes(ShoppingException::PRODUCT_ERROR);
        }

        $skus = $this->stockQueryService->getRepository()->findList($orderData->products->pluck('skuId')->unique()->toArray());

        // 验证状态
        foreach ($orderData->products as $productData) {
            $productData->setProduct(collect($products)->where('id', $productData->productId)->first());
            $productData->setSku(collect($skus)->where('id', $productData->skuId)->first());

            $productData->getProduct()->isAllowSale();
            $productData->getSku()->isAllowSale();
            $productData->getProduct()->isAllowNumberBuy($productData->num);
        }


        return $orderData;
    }


    /**
     * 拆分订单
     *
     * @param OrderData $orderData
     *
     * @return void
     */
    public function split(OrderData $orderData)
    {


    }


    /**
     * @param OrderData $orderData
     *
     * @return OrderData
     * @throws ShoppingException
     */
    public function validate(OrderData $orderData) : OrderData
    {
        foreach ($orderData->products as $productData) {

        }


        return $orderData;
    }


    protected function calculation()
    {

    }


    protected function check()
    {

    }

}
