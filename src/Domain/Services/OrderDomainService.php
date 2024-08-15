<?php

namespace RedJasmine\Shopping\Domain\Services;

use Illuminate\Support\Collection;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\Data\OrderData;
use RedJasmine\Shopping\Application\UserCases\Commands\Data\ProductData;
use RedJasmine\Shopping\Domain\Exceptions\ShoppingException;
use RedJasmine\Support\Foundation\Service\Service;

class OrderDomainService extends Service
{


    public function __construct(
        protected ProductCommandService     $productCommandService,
        protected ProductQueryService       $productQueryService,
        protected StockQueryService         $stockQueryService,
        protected StockCommandService       $stockCommandService,
        protected OrderCommandService       $orderCommandService,
        protected ProductPriceDomainService $productPriceDomainService
    )
    {

    }


    protected function validate(OrderData $orderData)
    {
        // 商品验证
        $this->product($orderData);


    }

    /**
     * 计算商品金额
     *
     * @param OrderData $orderData
     *
     * @return void
     */
    public function calculation(OrderData $orderData)
    {
        $this->validate($orderData);
        // 拆分订单
        $orders = $this->split($orderData);
        // 商品金额


        foreach ($orders as $order) {

            foreach ($order->products as $productData) {
                // TODO 通过一些中间件
                // 获取产品价格
                $price        = $this->productPriceDomainService->getPrice($productData->getProduct(), $productData->skuId);
                $productTotal = bcmul((string)$price, $productData->num, 2);
                $productData->additional([
                                             'price'  => $price->value(),
                                             'amount' => $productTotal
                                         ]);

                // TOD 获取优惠
            }

        }
        dd($orders->toArray());

        // 商品优惠

        // 是否涵盖邮费

    }


    /**
     * 结算
     *
     * @param OrderData $orderData
     *
     * @return void
     */
    public function check(OrderData $orderData)
    {


    }


    /**
     * 商品校验
     *
     * @param OrderData $orderData
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
     * @param OrderData $orderData
     *
     * @return Collection|OrderData[]
     */
    public function split(OrderData $orderData) : Collection
    {
        // 拆分订单
        $orders = collect([]);
        // 按买家拆分
        $productGroup = [];
        foreach ($orderData->products as $productData) {
            $splitKey                  = $this->getProductSplitKey($productData);
            $productGroup[$splitKey][] = $productData;
        }
        foreach ($productGroup as $splitKey => $products) {
            $order           = clone $orderData;
            $order->products = collect($products);
            $orders->push($order);
        }
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


    protected function toOrderCommand(OrderData $orderData)
    {
        $order        = new OrderCreateCommand();
        $order->buyer = $orderData->buyer;

    }


}
