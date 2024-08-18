<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Shopping\Application\UserCases\Commands\Data\OrderData;
use RedJasmine\Shopping\Application\UserCases\Commands\Data\ProductData;
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


    public function calculates(array|OrderData $orders)
    {
        // 如果是一个订单变成多个计算
        if ($orders instanceof OrderData) {
            $orders = [$orders];
        }

        foreach ($orders as $order) {
            $this->calculation($order);
        }


        // 计算订单优惠金额 （跨店活动）
        // 计算邮费


    }

    protected function calculation(OrderData $order)
    {
        // 获取商品价格

        // 计算税
        // 计算商品优惠金额
    }


    protected function productCalculation(ProductData $productData, OrderData $order)
    {
        // 商品中决定价格，主要因数规格、数量、渠道、VIP、
        // 营销中心是决定优惠金额
        // 物流中心主要是决定运费

        $productModel = $productData->getProduct();
        $skuId        = $productData->skuId;
        $price        = $this->productPriceDomainService->getPrice($productModel, $skuId);


        // TODO 公共价格计算

    }

}
