<?php

namespace RedJasmine\Order\Domains\Order\Application\Mappers;


use RedJasmine\Order\Domains\Order\Application\Data\OrderProductData;
use RedJasmine\Order\Domains\Order\Domain\Models\OrderProduct;

class OrderProductMapper
{


    public function fromData(OrderProductData $orderProductData, OrderProduct $orderProduct) : OrderProduct
    {
        $orderProduct->order_product_type     = $orderProductData->orderProductType;
        $orderProduct->shipping_type          = $orderProductData->shippingType;
        $orderProduct->product_type           = $orderProductData->productType;
        $orderProduct->product_id             = $orderProductData->productId;
        $orderProduct->sku_id                 = $orderProductData->skuId;
        $orderProduct->title                  = $orderProductData->title;
        $orderProduct->sku_name               = $orderProductData->skuName;
        $orderProduct->price                  = $orderProductData->price;
        $orderProduct->num                    = $orderProductData->num;
        $orderProduct->image                  = $orderProductData->image;
        $orderProduct->category_id            = $orderProductData->categoryId;
        $orderProduct->seller_category_id     = $orderProductData->sellerCategoryId;
        $orderProduct->outer_id               = $orderProductData->outerId;
        $orderProduct->outer_sku_id           = $orderProductData->outerSkuId;
        $orderProduct->seller_custom_status   = $orderProductData->sellerCustomStatus;
        $orderProduct->outer_order_product_id = $orderProductData->outerOrderProductId;
        $orderProduct->info->seller_remarks   = $orderProductData->info?->sellerRemarks;
        $orderProduct->info->seller_message   = $orderProductData->info?->sellerMessage;
        $orderProduct->info->buyer_remarks    = $orderProductData->info?->buyerRemarks;
        $orderProduct->info->buyer_message    = $orderProductData->info?->buyerMessage;
        $orderProduct->info->seller_extends   = $orderProductData->info?->sellerExtends;
        $orderProduct->info->buyer_extends    = $orderProductData->info?->buyerExtends;
        $orderProduct->info->other_extends    = $orderProductData->info?->otherExtends;
        $orderProduct->info->tools            = $orderProductData->info?->tools;
        return $orderProduct;
    }

}
