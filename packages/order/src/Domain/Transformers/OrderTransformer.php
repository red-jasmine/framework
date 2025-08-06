<?php

namespace RedJasmine\Order\Domain\Transformers;

use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Order\Domain\Data\OrderData;
use RedJasmine\Order\Domain\Data\OrderProductData;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderAddress;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;


class OrderTransformer implements TransformerInterface
{
    /**
     * @param  OrderData  $data
     * @param  Order  $order
     *
     * @return Order
     */
    public function transform($data, $order) : Order
    {

        $order->biz    = $data->biz;
        $order->seller = $data->seller;
        $order->buyer                     = $data->buyer;
        $order->guide                     = $data->guide;
        $order->channel                   = $data->channel;
        $order->store                     = $data->store;
        $order->title                     = $data->title;
        $order->order_type                = $data->orderType;
        $order->shipping_type             = $data->shippingType;
        $order->source                    = $data->source;
        $order->seller_custom_status      = $data->sellerCustomStatus;
        $order->freight_amount            = $data->freightAmount;
        $order->discount_amount           = $data->discountAmount;
        $order->client_type               = $data->clientType;
        $order->client_version            = $data->clientVersion;
        $order->client_ip                 = $data->clientIp;
        $order->outer_order_id            = $data->outerOrderId;
        $order->extension->seller_remarks = $data->sellerRemarks;
        $order->extension->seller_message = $data->sellerMessage;
        $order->extension->buyer_remarks  = $data->buyerRemarks;
        $order->extension->buyer_message  = $data->buyerMessage;
        $order->extension->seller_extra   = $data->sellerExtra;
        $order->extension->buyer_extra    = $data->buyerExtra;
        $order->extension->other_extra    = $data->otherExtra;
        $order->extension->tools          = $data->tools;


        $order->payment_timeout = $data->paymentTimeout;
        $order->accept_timeout  = $data->acceptTimeout;
        $order->confirm_timeout = $data->confirmTimeout;
        $order->rate_timeout    = $data->rateTimeout;


        // 转换商品项实体

        foreach ($data->products as $productData) {
            $order->addProduct($this->transformProduct($productData, $order->makeProduct()));
        }

        // 转换 地址
        if ($data->address) {
            $order->setAddress($this->transformAddress($data->address));
        }


        return $order;

    }


    public function transformProduct(OrderProductData $orderProductData, OrderProduct $orderProduct) : OrderProduct
    {
        $orderProduct->setSerialNumber($orderProductData->getSerialNumber() ?? $orderProductData->buildSerialNumber());
        $orderProduct->order_product_type              = $orderProductData->orderProductType;
        $orderProduct->shipping_type                   = $orderProductData->shippingType;
        $orderProduct->product_type                    = $orderProductData->productType;
        $orderProduct->product_id                      = $orderProductData->productId;
        $orderProduct->sku_id                          = $orderProductData->skuId;
        $orderProduct->title                           = $orderProductData->title;
        $orderProduct->sku_name                        = $orderProductData->skuName;
        $orderProduct->price                           = $orderProductData->price;
        $orderProduct->tax_rate                        = $orderProductData->texRate;
        $orderProduct->tax_amount                      = $orderProductData->taxAmount;
        $orderProduct->cost_price                      = $orderProductData->costPrice;
        $orderProduct->quantity                        = $orderProductData->quantity;
        $orderProduct->unit                            = $orderProductData->unit;
        $orderProduct->unit_quantity                   = $orderProductData->unitQuantity;
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
        $orderProduct->shopping_cart_id                = $orderProductData->shoppingCartId;
        $orderProduct->extension->seller_remarks       = $orderProductData->sellerRemarks;
        $orderProduct->extension->seller_message       = $orderProductData->sellerMessage;
        $orderProduct->extension->buyer_remarks        = $orderProductData->buyerRemarks;
        $orderProduct->extension->buyer_message        = $orderProductData->buyerMessage;
        $orderProduct->extension->seller_extra         = $orderProductData->sellerExtra;
        $orderProduct->extension->buyer_remarks        = $orderProductData->buyerExtra;
        $orderProduct->extension->other_extra          = $orderProductData->otherExtra;
        $orderProduct->extension->after_sales_services = $orderProductData->afterSalesServices;
        $orderProduct->extension->tools                = $orderProductData->tools;
        $orderProduct->extension->customized           = $orderProductData->customized;
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
