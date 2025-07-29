<?php

namespace RedJasmine\PointsMall\Infrastructure\Services\Transformers;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Data\OrderProductData;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

/**
 * 积分兑换订单创建命令转换器
 */
class PointsExchangeOrderCreateCommandTransformer
{
    /**
     * 转换订单数据为创建命令
     *
     * @param  PointsExchangeOrder  $exchangeOrder
     * @param  ProductInfo  $productInfo
     *
     * @return OrderCreateCommand
     */
    public function transform(PointsExchangeOrder $exchangeOrder, ProductInfo $productInfo) : OrderCreateCommand
    {

        $order         = new OrderCreateCommand();

        $order->buyer  = $exchangeOrder->user;
        $order->seller = $exchangeOrder->owner;

        $order->title        = ''; // TODO 订单标题
        $order->outerOrderId = null;
        //$order->clientIp       = $exchangeOrder->clie;
        //$order->clientType     = $exchangeOrder->clientType;
        //$order->clientVersion  = $exchangeOrder->clientVersion;
        $order->discountAmount = Money::parse(0, $exchangeOrder->total_amount_currency);
        $order->freightAmount  = Money::parse(0, $exchangeOrder->total_amount_currency);
        $order->orderType      = OrderTypeEnum::STANDARD->value;
        // TODO
        $order->channel  = null;
        $order->store    = null;
        $order->address  = null;
        $order->products = [];

        $order->shippingType = $productInfo->shippingTypes[0];

        // 获取价格
        $order->currency = $exchangeOrder->price->getCurrency();
        $product         = new OrderProductData();

        $product->quantity            = $exchangeOrder->quantity;
        $product->orderProductType    = $productInfo->productType;
        $product->shippingType        = $productInfo->shippingTypes[0];// TODO 获取根据用户选择的配送方式
        $product->title               = $productInfo->title;
        $product->skuName             = $productInfo->propertiesName;
        $product->productType         = $productInfo->product->type;
        $product->productId           = $productInfo->product->id;
        $product->skuId               = $productInfo->product->skuId;
        $product->categoryId          = $productInfo->categoryId;
        $product->brandId             = $productInfo->brandId;
        $product->productGroupId      = $productInfo->productGroupId;
        $product->outerProductId      = $productInfo->outerId;
        $product->outerProductId      = $productInfo->barcode;
        $product->price               = $exchangeOrder->price;
        $product->discountAmount      = Money::parse(0, $exchangeOrder->total_amount_currency);
        $product->costPrice           = Money::parse(0, $exchangeOrder->total_amount_currency);
        $product->image               = $productInfo->image;
        $product->outerProductId      = null;
        $product->outerOrderProductId = null;
        $product->buyerRemarks        = null;
        $product->buyerMessage        = null;

        // TODO
        //$product->outerProductId  = $productData->getProduct()->outer_id;
        //$product->outerSkuId      = $productData->getSku()->outer_id;
        //$product->barcode         = $productData->getSku()->barcode ?? $productData->getProduct()->barcode ?? null;
        //$product->promiseServices = $productData->getProduct()->promise_services ?? null;
        //$product->buyerMessage    = $productData->buyerMessage ?? null;
        //$product->buyerRemarks    = $productData->buyerRemarks ?? null;
        //$product->buyerExtra      = $productData->buyerExtra ?? null;
        //$product->otherExtra      = null; // TODO
        $product->customized =  [];

        // TODO 优惠信息的存储

        $order->products[] = $product;


        return $order;
    }
} 