<?php

namespace RedJasmine\Order\Domains\Order\Application\Mappers;


use RedJasmine\Order\Domains\Order\Application\Data\OrderData;
use RedJasmine\Order\Domains\Order\Domain\Models\Order;

class OrderMapper
{

    public function fromData(OrderData $orderData, Order $order) : Order
    {

        $order->seller               = $orderData->seller;
        $order->buyer                = $orderData->buyer;
        $order->title                = $orderData->title;
        $order->order_type           = $orderData->orderType;
        $order->shipping_type        = $orderData->shippingType;
        $order->source               = $orderData->source;
        $order->seller_custom_status = $orderData->sellerCustomStatus;
        $order->freight_amount       = $orderData->freightAmount;
        $order->discount_amount      = $orderData->discountAmount;
        $order->contact              = $orderData->contact;
        $order->password             = $orderData->password;
        $order->client_type          = $orderData->clientType;
        $order->client_ip            = $orderData->clientIp;
        $order->guide                = $orderData->guide;
        $order->store_type           = $orderData->store?->type;
        $order->store_id             = $orderData->store?->id;
        $order->channel_type         = $orderData->channel?->type;
        $order->channel_id           = $orderData->channel?->id;
        $order->outer_order_id       = $orderData->outerOrderId;
        $order->info->seller_remarks = $orderData->info?->sellerRemarks;
        $order->info->seller_message = $orderData->info?->sellerMessage;
        $order->info->buyer_remarks  = $orderData->info?->buyerRemarks;
        $order->info->buyer_message  = $orderData->info?->buyerMessage;
        $order->info->seller_extends = $orderData->info?->sellerExtends;
        $order->info->buyer_extends  = $orderData->info?->buyerExtends;
        $order->info->other_extends  = $orderData->info?->otherExtends;
        $order->info->tools          = $orderData->info?->tools;


        return $order;
    }

    public function fromModel(Order $order) : OrderData
    {
        return OrderData::from($order->toArray());
    }
}
