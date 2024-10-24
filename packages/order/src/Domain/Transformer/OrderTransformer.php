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
        $order                       = $order ?? Order::newModel();
        $order->seller               = $orderData->seller;
        $order->buyer                = $orderData->buyer;
        $order->guide                = $orderData->guide;
        $order->channel              = $orderData->channel;
        $order->store                = $orderData->store;
        $order->title                = $orderData->title;
        $order->order_type           = $orderData->orderType;
        $order->source_type          = $orderData->sourceType;
        $order->source_id            = $orderData->sourceId;
        $order->seller_custom_status = $orderData->sellerCustomStatus;
        $order->freight_amount       = $orderData->freightAmount;
        $order->discount_amount      = $orderData->discountAmount;
        $order->contact              = $orderData->contact;
        $order->password             = $orderData->password;
        $order->client_type          = $orderData->clientType;
        $order->client_version       = $orderData->clientVersion;
        $order->client_ip            = $orderData->clientIp;
        $order->outer_order_id       = $orderData->outerOrderId;
        $order->info->seller_remarks = $orderData->sellerRemarks;
        $order->info->seller_message = $orderData->sellerMessage;
        $order->info->buyer_remarks  = $orderData->buyerRemarks;
        $order->info->buyer_message  = $orderData->buyerMessage;
        $order->info->seller_expands = $orderData->sellerExpands;
        $order->info->buyer_expands  = $orderData->buyerExpands;
        $order->info->other_expands  = $orderData->otherExpands;
        $order->info->tools          = $orderData->tools;


        // 转换商品项实体

        foreach ($orderData->products as $productData) {
            $order->addProduct($this->transformProduct($productData));
        }

        // 转换 地址

        if ($orderData->address) {

            $order->setRelation('address', $this->transformAddress($orderData->address));

        }


        return $order;

    }


    public function transformProduct(OrderProductData $orderProductData, ?OrderProduct $orderProduct = null) : OrderProduct
    {
        $orderProduct = $orderProduct ?? OrderProduct::newModel();

        $orderProduct->order_product_type        = $orderProductData->orderProductType;
        $orderProduct->shipping_type             = $orderProductData->shippingType;
        $orderProduct->product_type              = $orderProductData->productType;
        $orderProduct->product_id                = $orderProductData->productId;
        $orderProduct->sku_id                    = $orderProductData->skuId;
        $orderProduct->title                     = $orderProductData->title;
        $orderProduct->sku_name                  = $orderProductData->skuName;
        $orderProduct->price                     = $orderProductData->price;
        $orderProduct->cost_price                = $orderProductData->costPrice;
        $orderProduct->num                       = $orderProductData->num;
        $orderProduct->unit                      = $orderProductData->unit;
        $orderProduct->unit_quantity             = $orderProductData->unitQuantity;
        $orderProduct->tax_amount                = $orderProductData->taxAmount;
        $orderProduct->discount_amount           = $orderProductData->discountAmount;
        $orderProduct->image                     = $orderProductData->image;
        $orderProduct->category_id               = $orderProductData->categoryId;
        $orderProduct->product_group_id          = $orderProductData->productGroupId;
        $orderProduct->outer_id                  = $orderProductData->outerId;
        $orderProduct->outer_sku_id              = $orderProductData->outerSkuId;
        $orderProduct->seller_custom_status      = $orderProductData->sellerCustomStatus;
        $orderProduct->outer_order_product_id    = $orderProductData->outerOrderProductId;
        $orderProduct->info->seller_remarks      = $orderProductData->sellerRemarks;
        $orderProduct->info->seller_message      = $orderProductData->sellerMessage;
        $orderProduct->info->buyer_remarks       = $orderProductData->buyerRemarks;
        $orderProduct->info->buyer_message       = $orderProductData->buyerMessage;
        $orderProduct->info->seller_expands      = $orderProductData->sellerExpands;
        $orderProduct->info->buyer_remarks       = $orderProductData->buyerExpands;
        $orderProduct->info->other_expands       = $orderProductData->otherExpands;
        $orderProduct->info->after_sales_services = $orderProductData->afterSalesServices;
        $orderProduct->info->tools               = $orderProductData->tools;
        $orderProduct->info->form                = $orderProductData->form;
        return $orderProduct;
    }


    public function transformAddress(OrderAddressData $orderAddressData, ?OrderAddress $orderAddress = null) : OrderAddress
    {
        $orderAddress = $orderAddress ?? OrderAddress::newModel();

        $orderAddress->fill($orderAddressData->toArray());
        return $orderAddress;
    }
}
