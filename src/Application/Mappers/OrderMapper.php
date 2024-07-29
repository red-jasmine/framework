<?php

namespace RedJasmine\Order\Application\Mappers;


use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Order;

class OrderMapper
{

    public function fromData(OrderCreateCommand $orderData, Order $order) : Order
    {

        $order->seller               = $orderData->seller;
        $order->buyer                = $orderData->buyer;
        $order->title                = $orderData->title;
        $order->order_type           = $orderData->orderType;
        $order->pay_type             = $orderData->payType;
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
        $order->info->seller_expands = $orderData->info?->sellerExpands;
        $order->info->buyer_expands  = $orderData->info?->buyerExpands;
        $order->info->other_expands  = $orderData->info?->otherExpands;
        $order->info->tools          = $orderData->info?->tools;


        return $order;
    }

    // public function fromModel(Order $order) : OrderData
    // {
    //     return OrderData::from($order->toArray());
    // }
}
