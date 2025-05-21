<?php

namespace RedJasmine\Shopping\Domain\Orders\Transformers;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Data\OrderProductData;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Shopping\Domain\Orders\Data\OrderData;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class OrderCreateCommandTransformer
{
    public function transform(OrderData $orderData) : OrderCreateCommand
    {
        $order                = new OrderCreateCommand();
        $order->buyer         = $orderData->buyer;
        $order->seller        = $orderData->products->first()->getProduct()->owner;
        $order->contact       = $orderData->contact;
        $order->password      = $orderData->password;
        $order->title         = ''; // TODO 订单标题
        $order->outerOrderId  = $orderData->outerOrderId;
        $order->clientIp      = $orderData->clientIp;
        $order->clientType    = $orderData->clientType;
        $order->clientVersion = $orderData->clientVersion;
        $order->orderType     = OrderTypeEnum::STANDARD;
        // TODO
        $order->channel  = null;
        $order->store    = null;
        $order->address  = null; // TODO
        $order->products = collect();
        foreach ($orderData->products as $productData) {
            // 获取价格
            $additionalData            = $productData->getAdditionalData();
            $price                     = MoneyOld::make($additionalData['price']);
            $product                   = new OrderProductData();
            $product->quantity         = $productData->quantity;
            $product->orderProductType = $productData->getProduct()->product_type;
            $order->shippingType       = $product->shippingType = $productData->getProduct()->shipping_type;
            $product->title            = $productData->getProduct()->title;
            $product->skuName          = $productData->getSku()->properties_name;
            $product->productType      = 'product';
            $product->productId        = $productData->getProduct()->id;
            $product->skuId            = $productData->getSku()->id;
            $product->price            = $price;
            $product->costPrice        = new MoneyOld($productData->getSku()->cost_price ?? 0);
            $product->categoryId       = $productData->getProduct()->category_id;
            $product->brandId          = $productData->getProduct()->brand_id;
            $product->productGroupId   = $productData->getProduct()->product_group_id;
            $product->image            = $productData->getSku()->image ?? $productData->getProduct()->image ?? null;
            $product->outerProductId   = $productData->getProduct()->outer_id;
            $product->outerSkuId       = $productData->getSku()->outer_id;
            $product->barcode          = $productData->getSku()->barcode ?? $productData->getProduct()->barcode ?? null;
            $product->promiseServices  = $productData->getProduct()->promise_services ?? null;
            $product->buyerMessage     = $productData->buyerMessage ?? null;
            $product->buyerRemarks     = $productData->buyerRemarks ?? null;
            $product->buyerExtra      = $productData->buyerExtra ?? null;
            $product->otherExtra      = null; // TODO
            $product->tools            = $productData->tools ?? null;


            $product->additional([
                'sku'     => $productData->getSku(),
                'product' => $productData->getProduct(),

            ]);
            $order->products->push($product);

        }

        return $order;
    }

}
