<?php

namespace RedJasmine\Shopping\Domain\Orders;

use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Shopping\Domain\Orders\Data\OrderData;
use RedJasmine\Shopping\Domain\Orders\Data\OrdersData;
use RedJasmine\Shopping\Domain\Orders\Data\ProductData;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderProductDiscountHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderProductPriceHook;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 商城订单金额计算服务
 */
class OrderCalculationService extends Service
{

    public function __construct(
        protected ProductPriceDomainService $productPriceDomainService
    ) {
    }


    /**
     * 订单金额计算
     *
     * @param  OrdersData  $orders
     *
     * @return OrdersData
     */
    public function calculates(OrdersData $orders) : OrdersData
    {
        foreach ($orders->orders as $order) {
            $this->calculationOrder($order);
        }

        // 计算订单优惠金额 （跨店活动） ? TODO
        $orders->total();

        return $orders;

    }


    /**
     * 订单金额计算
     *
     * @param  OrderData  $order
     *
     * @return OrderData
     */
    protected function calculationOrder(OrderData $order) : OrderData
    {
        // 获取商品价格
        $this->getOrderProductPrices($order);
        // 获取优惠
        $this->calculationOrderDiscounts($order);
        // 计算运费
        $this->calculationOrderFreight($order);
        // 计算税费
        $this->calculationOrderTaxes($order);
        // 计算订单金额
        $this->calculateOrderTotal($order);



        return $order;

    }


    /**
     * 获取商品基础价格
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function getOrderProductPrices(OrderData $order) : void
    {
        foreach ($order->products as $product) {
            $this->calculationProductPrice($product, $order);
        }

    }

    /**
     * 计算订单税费
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function calculationOrderTaxes(OrderData $order) : void
    {
        foreach ($order->products as $product) {
            // 计算税费
            $product->additional([
                'tax_amount' => Money::make(),

            ]);

        }
    }

    /**
     * 计算单品价格
     *
     * @param  ProductData  $productData
     * @param  OrderData  $orderData
     *
     * @return void
     */
    protected function calculationProductPrice(ProductData $productData, OrderData $orderData) : void
    {

        $productPriceDTO            = new  ProductPriceData;
        $productPriceDTO->productId = $productData->productId;
        $productPriceDTO->skuId     = $productData->skuId;
        $productPriceDTO->quantity  = $productData->quantity;
        $productPriceDTO->store     = $orderData->store;
        $productPriceDTO->channel   = $orderData->channel;
        $productPriceDTO->guide     = $orderData->guide;

        $productPriceDTO->additional([
            'orders' => $orderData
        ]);

        // 商品中决定价格，主要因数规格、数量、渠道、VIP、
        $price = ShoppingOrderProductPriceHook::hook($productPriceDTO,
            fn() => $this->productPriceDomainService->getPrice($productPriceDTO));


        $productAmount = (clone $price)->mul($productData->quantity);

        $productData->additional([
            'price'          => $price,
            'product_amount' => $productAmount,
        ]);

    }

    /**
     * 计算单品优惠
     *
     * @param  ProductData  $productData
     * @param  OrderData  $orderData
     *
     * @return void
     */
    protected function calculationProductDiscount(ProductData $productData, OrderData $orderData) : void
    {
        $productPriceDTO            = new  ProductPriceData;
        $productPriceDTO->productId = $productData->productId;
        $productPriceDTO->skuId     = $productData->skuId;
        $productPriceDTO->quantity  = $productData->quantity;
        $productPriceDTO->store     = $orderData->store;
        $productPriceDTO->channel   = $orderData->channel;
        $productPriceDTO->guide     = $orderData->guide;

        $productPriceDTO->additional([
            'orders' => $orderData
        ]);
        // TODO   discountBreakdown 这里需要 优惠明细
        $discountAmount = ShoppingOrderProductDiscountHook::hook($productPriceDTO, static fn() => Money::make());


        $productData->additional([
            'discount_amount' => $discountAmount,
        ]);

    }

    /**
     * 计算优惠
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function calculationOrderDiscounts(OrderData $order) : void
    {

        // 计算单品优惠
        foreach ($order->products as $product) {
            // 计算单品优惠
            $this->calculationProductDiscount($product, $order);
        }

        // 计算订单优惠
        $order->additional([
            'discount_amount' => Money::make(),
        ]);

    }

    /**
     * 计算邮费
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function calculationOrderFreight(OrderData $order) : void
    {
        // TODO
        // 计算订单运费
        $order->additional([
            'freight_amount' => Money::make(0),
        ]);

    }

    protected function calculateOrderTotal(OrderData $order) : void
    {
        $productPayableAmount = Money::make(0);
        // 计算单品
        foreach ($order->products as $product) {

            $amounts = $product->getAdditionalData();
            /**
             * 商品金额
             * @var $productAmount Money
             */
            $productAmount = $amounts['product_amount'];
            /**
             * 优惠金额
             * @var $discountAmount Money
             */
            $productDiscountAmount = $amounts['discount_amount'];
            /**
             * 税
             * @var $taxAmount Money
             */
            $taxAmount = $amounts['tax_amount'];

            // 计算商品
            $payableAmount = Money::make(0);

            $payableAmount->add($productAmount)
                          ->add($taxAmount)
                          ->sub($productDiscountAmount);

            $product->additional([
                'payable_amount' => $payableAmount,
            ]);

            $productPayableAmount->add($payableAmount);

        }


        $orderAmounts = $order->getAdditionalData();
        // 计算订单应付金额
        $payableAmount = Money::make(0);
        /**
         * @var $freightAmount Money
         */
        $freightAmount = $orderAmounts['freight_amount'];
        /**
         * @var $freightAmount Money
         */
        $discountAmount = $orderAmounts['discount_amount'];

        $payableAmount->add($productPayableAmount)->add($freightAmount)->sub($discountAmount);

        $order->additional([
            'product_payable_amount' => $productPayableAmount,
            'payable_amount'         => $payableAmount
        ]);

    }
}
