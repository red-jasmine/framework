<?php

namespace RedJasmine\Order\Domain\Transformer;

use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Order\Domain\Data\OrderData;
use RedJasmine\Order\Domain\Data\OrderProductData;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderAddress;
use RedJasmine\Order\Domain\Models\OrderProduct;


class OrderTransformer
{
    public function transform(OrderData $orderData, ?Order $order = null) : Order
    {
        $order                            = $order ?? Order::make(
            [
                'app_id'    => $orderData->appId,
                'seller_id' => $orderData->seller->getID(),
                'buyer_id'  => $orderData->buyer->getID(),
            ]
        );
        $order->currency                  = $orderData->currency;
        $order->app_id                    = $orderData->appId;
        $order->seller                    = $orderData->seller;
        $order->buyer                     = $orderData->buyer;
        $order->guide                     = $orderData->guide;
        $order->channel                   = $orderData->channel;
        $order->store                     = $orderData->store;
        $order->title                     = $orderData->title;
        $order->order_type                = $orderData->orderType;
        $order->shipping_type             = $orderData->shippingType;
        $order->source_type               = $orderData->sourceType;
        $order->source_id                 = $orderData->sourceId;
        $order->seller_custom_status      = $orderData->sellerCustomStatus;
        $order->freight_amount            = $orderData->freightAmount;
        $order->discount_amount           = $orderData->discountAmount;
        $order->contact                   = $orderData->contact;
        $order->password                  = $orderData->password;
        $order->client_type               = $orderData->clientType;
        $order->client_version            = $orderData->clientVersion;
        $order->client_ip                 = $orderData->clientIp;
        $order->outer_order_id            = $orderData->outerOrderId;
        $order->extension->seller_remarks = $orderData->sellerRemarks;
        $order->extension->seller_message = $orderData->sellerMessage;
        $order->extension->buyer_remarks  = $orderData->buyerRemarks;
        $order->extension->buyer_message  = $orderData->buyerMessage;
        $order->extension->seller_extra  = $orderData->sellerExtra;
        $order->extension->buyer_extra   = $orderData->buyerExtra;
        $order->extension->other_extra   = $orderData->otherExtra;
        $order->extension->tools          = $orderData->tools;


        $order->payment_wait_max_time = $orderData->paymentWaitMaxTime;
        $order->accept_wait_max_time  = $orderData->acceptWaitMaxTime;
        $order->confirm_wait_max_time = $orderData->confirmWaitMaxTime;
        $order->rate_wait_max_time    = $orderData->rateWaitMaxTime;


        // 转换商品项实体

        foreach ($orderData->products as $productData) {
            $order->addProduct($this->transformProduct($productData));
        }

        // 转换 地址
        if ($orderData->address) {
            $order->setAddress($this->transformAddress($orderData->address));
        }


        return $order;

    }


    public function transformProduct(
        OrderProductData $orderProductData,
        ?OrderProduct $orderProduct = null
    ) : OrderProduct {
        $orderProduct = $orderProduct ?? OrderProduct::make();

        $orderProduct->order_product_type              = $orderProductData->orderProductType;
        $orderProduct->shipping_type                   = $orderProductData->shippingType;
        $orderProduct->product_type                    = $orderProductData->productType;
        $orderProduct->product_id                      = $orderProductData->productId;
        $orderProduct->sku_id                          = $orderProductData->skuId;
        $orderProduct->title                           = $orderProductData->title;
        $orderProduct->sku_name                        = $orderProductData->skuName;
        $orderProduct->price                           = $orderProductData->price;
        $orderProduct->cost_price                      = $orderProductData->costPrice;
        $orderProduct->quantity                        = $orderProductData->quantity;
        $orderProduct->unit                            = $orderProductData->unit;
        $orderProduct->unit_quantity                   = $orderProductData->unitQuantity;
        $orderProduct->tax_amount                      = $orderProductData->taxAmount;
        $orderProduct->discount_amount                 = $orderProductData->discountAmount;
        $orderProduct->image                           = $orderProductData->image;
        $orderProduct->category_id                     = $orderProductData->categoryId;
        $orderProduct->brand_id                        = $orderProductData->brandId;
        $orderProduct->product_group_id                = $orderProductData->productGroupId;
        $orderProduct->outer_product_id                = $orderProductData->outerProductId;
        $orderProduct->outer_sku_id                    = $orderProductData->outerSkuId;
        $orderProduct->gift_point                      = $orderProductData->giftPoint;
        $orderProduct->seller_custom_status            = $orderProductData->sellerCustomStatus;
        $orderProduct->outer_order_product_id          = $orderProductData->outerOrderProductId;
        $orderProduct->extension->seller_remarks       = $orderProductData->sellerRemarks;
        $orderProduct->extension->seller_message       = $orderProductData->sellerMessage;
        $orderProduct->extension->buyer_remarks        = $orderProductData->buyerRemarks;
        $orderProduct->extension->buyer_message        = $orderProductData->buyerMessage;
        $orderProduct->extension->seller_extra        = $orderProductData->sellerExtra;
        $orderProduct->extension->buyer_remarks        = $orderProductData->buyerExtra;
        $orderProduct->extension->other_extra         = $orderProductData->otherExtra;
        $orderProduct->extension->after_sales_services = $orderProductData->afterSalesServices;
        $orderProduct->extension->tools                = $orderProductData->tools;
        $orderProduct->extension->form                 = $orderProductData->form;
        return $orderProduct;
    }


    public function transformAddress(
        OrderAddressData $orderAddressData,
        ?OrderAddress $orderAddress = null
    ) : OrderAddress {
        $orderAddress = $orderAddress ?? OrderAddress::make();

        $orderAddress->fill($orderAddressData->toArray());
        return $orderAddress;
    }
}
