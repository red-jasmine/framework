<?php

namespace RedJasmine\Shopping\Domain\Orders;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Domain\Data\OrdersData;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\ProductData;
use RedJasmine\Shopping\Exceptions\ShoppingException;
use RedJasmine\Support\Foundation\Service\Service;

class OrderDomainService extends Service
{


    public function __construct(
        protected ProductCommandService $productCommandService,
        protected ProductQueryService $productQueryService,
        protected StockQueryService $stockQueryService,
        protected StockCommandService $stockCommandService,
        protected OrderCommandService $orderCommandService,
        protected ProductPriceDomainService $productPriceDomainService,
        protected OrderCalculationService $orderCalculationService,
        protected OrderBuyService $orderBuyService,
    ) {

    }

    public function buy(OrderData $orderData) : \Illuminate\Support\Collection
    {
        $this->init($orderData);
        $orders      = $this->split($orderData);
        $orders      = $this->orderCalculationService->calculates($orders);
        $orderModels = $this->orderBuyService->buy($orders);
        return $orderModels;
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
        $products      = $this->productQueryService->getRepository()->findList($productIdList);

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
            $productData->getProduct()->isAllowNumberBuy($productData->num);
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
        // 拆分订单
        $orders       = new OrdersData();
        $orderCollect = collect();
        // 按买家拆分
        $productGroup = [];
        foreach ($orderData->products as $productData) {
            $splitKey                  = $this->getProductSplitKey($productData);
            $productGroup[$splitKey][] = $productData;
        }
        foreach ($productGroup as $splitKey => $products) {
            $order  = clone $orderData;
            $seller = $products[0]->getProduct()->owner;
            $order->setSeller($seller);
            $order->products = collect($products);
            $orderCollect->push($order);
        }
        $orders->setOrders($orderCollect);

        return $orders;
    }

    protected function getProductSplitKey(ProductData $productData) : string
    {
        $implode = [
            $productData->getProduct()->owner->getType(),
            $productData->getProduct()->owner->getID(),
        ];
        // 判断是否存特殊的逻辑
        return implode('|', $implode);
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

    protected function validate(OrderData $orderData)
    {
        // 商品验证
        $this->product($orderData);
    }


}
