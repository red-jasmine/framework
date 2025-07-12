<?php

namespace RedJasmine\Shopping\Infrastructure\Services\Transformers;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Data\OrderProductData;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Shopping\Domain\Data\OrderData;

class OrderCreateCommandTransformer
{
    public function transform(OrderData $orderData) : OrderCreateCommand
    {
        $order         = new OrderCreateCommand();
        $order->buyer  = $orderData->buyer;
        $order->seller = $orderData->seller;

        $order->title          = ''; // TODO 订单标题
        $order->outerOrderId   = $orderData->outerOrderId;
        $order->clientIp       = $orderData->clientIp;
        $order->clientType     = $orderData->clientType;
        $order->clientVersion  = $orderData->clientVersion;
        $order->discountAmount = $orderData->getOrderAmount()->discountAmount;
        $order->freightAmount  = $orderData->getOrderAmount()->freightAmount;
        $order->orderType      = OrderTypeEnum::STANDARD->value;
        // TODO
        $order->channel  = null;
        $order->store    = null;
        $order->address  = null;
        $order->products = [];
        foreach ($orderData->products as $productData) {
            $order->shippingType = $productData->getProductInfo()->shippingTypes[0];

            // 获取价格
            $order->currency = $productData->getProductAmount()->price->getCurrency();
            $product         = new OrderProductData();
            $product->setSerialNumber($productData->getSerialNumber()); // 设置序列号
            $product->quantity            = $productData->quantity;
            $product->orderProductType    = $productData->getProductInfo()->productType;
            $product->shippingType        = $productData->getProductInfo()->shippingTypes[0];// TODO 获取根据用户选择的配送方式
            $product->title               = $productData->getProductInfo()->title;
            $product->skuName             = $productData->getProductInfo()->propertiesName;
            $product->productType         = $productData->getProductInfo()->product->type;
            $product->productId           = $productData->getProductInfo()->product->id;
            $product->skuId               = $productData->getProductInfo()->product->skuId;
            $product->price               = $productData->getProductAmount()->price;
            $product->discountAmount      = $productData->getProductAmount()->discountAmount;
            $product->costPrice           = $productData->getProductAmount()->getCostPrice();
            $product->image               = $productData->getProductInfo()->image;
            $product->outerProductId      = $product->outerProductId;
            $product->outerOrderProductId = $product->outerOrderProductId;
            $product->buyerRemarks        = $product->buyerRemarks;
            $product->buyerMessage        = $product->buyerMessage;
            //$product->categoryId      = $productData->getProduct()->category_id;
            //$product->brandId         = $productData->getProduct()->brand_id;
            //$product->productGroupId  = $productData->getProduct()->product_group_id;
            //$product->outerProductId  = $productData->getProduct()->outer_id;
            //$product->outerSkuId      = $productData->getSku()->outer_id;
            //$product->barcode         = $productData->getSku()->barcode ?? $productData->getProduct()->barcode ?? null;
            //$product->promiseServices = $productData->getProduct()->promise_services ?? null;
            //$product->buyerMessage    = $productData->buyerMessage ?? null;
            //$product->buyerRemarks    = $productData->buyerRemarks ?? null;
            //$product->buyerExtra      = $productData->buyerExtra ?? null;
            //$product->otherExtra      = null; // TODO
            $product->customized = $productData->customized ?? [];

            // TODO 优惠信息的存储

            $order->products[] = $product;

        }

        return $order;
    }

}
