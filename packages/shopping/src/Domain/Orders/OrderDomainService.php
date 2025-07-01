<?php

namespace RedJasmine\Shopping\Domain\Orders;

use Illuminate\Support\Collection;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Shopping\Application\Services\ShoppingOrderCommandService;
use RedJasmine\Shopping\Domain\Orders\Data\OrderData;
use RedJasmine\Shopping\Domain\Orders\Data\OrdersData;
use RedJasmine\Shopping\Exceptions\ShoppingException;
use RedJasmine\Support\Foundation\Service\Service;
use Throwable;

class OrderDomainService extends Service
{


    public function __construct(
        protected ProductApplicationService $productCommandService,
        protected StockQueryService $stockQueryService,
        protected StockApplicationService $stockCommandService,
        protected ShoppingOrderCommandService $orderCommandService,
        protected ProductPriceDomainService $productPriceDomainService,
        protected OrderCalculationService $orderCalculationService,
        protected OrderBuyService $orderBuyService,
    ) {

    }

    /**
     * @param  OrderData  $orderData
     *
     * @return Collection
     * @throws Throwable
     */
    public function buy(OrderData $orderData) : Collection
    {
        $this->init($orderData);
        $orders = $this->split($orderData);
        $orders = $this->orderCalculationService->calculates($orders);
        return $this->orderBuyService->buy($orders);

    }

    protected function init(OrderData $orderData) : void
    {
        $this->product($orderData);
    }

    /**
     * 商品校验
     *
     * @param  OrderData  $orderData
     *
     * @return OrderData
     * @throws ShoppingException
     * @throws ProductException
     * @throws StockException
     */
    public function product(OrderData $orderData) : OrderData
    {

        // 验证商品
        $productIdList = $orderData->products->pluck('productId')->unique()->toArray();
        $products      = $this->productCommandService->readRepository->findList($productIdList);

        if (count($productIdList) !== count($products)) {
            throw  ShoppingException::newFromCodes(ShoppingException::PRODUCT_ERROR);
        }

        $skus = $this->stockQueryService->getRepository()->findList($orderData->products->pluck('skuId')->unique()->toArray());

        // 验证状态
        foreach ($orderData->products as $productData) {

            $product = collect($products)->where('id', $productData->productId)->first();
            $sku     = collect($skus)->where('id', $productData->skuId)->first();

            if ($sku->product_id !== $productData->productId) {
                throw  ShoppingException::newFromCodes(ShoppingException::PRODUCT_SKU_NOT_MATCHING);
            }
            $productData->setProduct($product);
            $productData->setSku($sku);

            $productData->getProduct()->isAllowSale();
            $productData->getSku()->isAllowSale();
            $productData->getProduct()->isAllowNumberBuy($productData->quantity);
        }


        return $orderData;
    }

    /**
     * 拆分订单
     *
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    public function split(OrderData $orderData) : OrdersData
    {
        return new OrderSplitService()->split($orderData);
    }


    /**
     * 订单金额计算
     *
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    public function calculates(OrderData $orderData) : OrdersData
    {
        $this->init($orderData);
        $orders = $this->split($orderData);

        return $this->orderCalculationService->calculates($orders);
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return void
     * @throws ProductException
     * @throws ShoppingException
     * @throws StockException
     */
    protected function validate(OrderData $orderData) : void
    {
        // 商品验证
        $this->product($orderData);
    }


}
