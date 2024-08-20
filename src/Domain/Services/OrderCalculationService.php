<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrdersData;
use RedJasmine\Shopping\Domain\Data\ProductData;
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

    protected function calculationOrder(OrderData $order) : void
    {
        // 获取商品价格
        foreach ($order->products as $product) {
            // 获取商品价格
            $this->calculationProductPrice($product, $order);
            // 计算单品优惠
            $this->calculationProductDiscount($product, $order);
        }

        // 获取优惠金额
        $this->calculationDiscount($order);

        // 获取邮费
        $this->calculationFreight($order);


        // 计算订单金额
        $this->check($order);

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
        $productPriceDTO->num       = $productData->num;
        $productPriceDTO->store     = $orderData->store;
        $productPriceDTO->channel   = $orderData->channel;
        $productPriceDTO->guide     = $orderData->guide;


        // 商品中决定价格，主要因数规格、数量、渠道、VIP、
        $price         = $this->productPriceDomainService->getPrice($productPriceDTO);
        $productAmount = Amount::make(bcmul($price->value(), $productData->num, 2));
        $taxAmount     = Amount::make(0); // TODO

        $productData->additional([
            'price'          => $price->value(),
            'product_amount' => $productAmount->value(),
            'tax_amount'     => $taxAmount->value(),
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
        // TODO
        $productData->additional([
            'discount_amount' => Amount::make(0)->value(),

        ]);

    }

    /**
     * 计算优惠
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function calculationDiscount(OrderData $order) : void
    {

        // 计算订单优惠
        $order->additional([
            'discount_amount' => Amount::make(0)->value(),
        ]);

    }

    /**
     * 计算邮费
     *
     * @param  OrderData  $order
     *
     * @return void
     */
    protected function calculationFreight(OrderData $order) : void
    {
        // TODO
        // 计算订单运费
        $order->additional([
            'freight_amount' => Amount::make(0)->value(),
        ]);

    }

    protected function check(OrderData $order) : void
    {
        $productPayableAmount = Amount::make(0);
        // 计算单品
        foreach ($order->products as $product) {

            $amounts = $product->getAdditionalData();
            /**
             * 商品金额
             * @var $productAmount Amount
             */
            $productAmount = $amounts['product_amount'];
            /**
             * 优惠金额
             * @var $discountAmount Amount
             */
            $productDiscountAmount = $amounts['discount_amount'];
            /**
             * 税
             * @var $taxAmount Amount
             */
            $taxAmount = $amounts['tax_amount'];

            // 计算商品
            $payableAmount = Amount::make(0);

            $payableAmount->add($productAmount)
                          ->add($taxAmount)
                          ->sub($productDiscountAmount);

            $product->additional([
                'payable_amount' => $payableAmount->value(),
            ]);

            $productPayableAmount->add($payableAmount);

        }


        $orderAmounts = $order->getAdditionalData();
        // 计算订单应付金额
        $payableAmount = Amount::make(0);
        /**
         * @var $freightAmount Amount
         */
        $freightAmount = $orderAmounts['freight_amount'];
        /**
         * @var $freightAmount Amount
         */
        $discountAmount = $orderAmounts['discount_amount'];

        $payableAmount->add($productPayableAmount)->add($freightAmount)->sub($discountAmount);

        $order->additional([
            'product_payable_amount' => $productPayableAmount->value(),
            'payable_amount'         => $payableAmount->value()
        ]);

    }
}
