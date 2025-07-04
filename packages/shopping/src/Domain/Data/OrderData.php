<?php

namespace RedJasmine\Shopping\Domain\Data;

use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Support\Contracts\UserInterface;

class OrderData extends PurchaseFactor
{


    /**
     * 卖家
     * @var UserInterface|null
     */
    public ?UserInterface $seller;
    /**
     * 订单标题
     * @var string|null
     */
    public ?string $title = null;

    /**
     * @var OrderProductData[]
     */
    public array $products;

    /**
     * 客户端类型
     * @var string|null
     */
    public ?string $clientType    = null;
    public ?string $clientVersion = null;
    public ?string $clientIp      = null;
    /**
     * 来源
     * @var string|null
     */
    public ?string $sourceType = null;
    public ?string $sourceId   = null;

    public ?string $sellerRemarks = null;
    public ?string $sellerMessage = null;
    public ?string $buyerRemarks  = null;
    public ?string $buyerMessage  = null;
    public ?array  $sellerExtra   = null;
    public ?array  $buyerExtra    = null;
    public ?array  $otherExtra    = null;
    public ?array  $tools         = null;

    public ?string $outerOrderId = null;
    /**
     * 地址
     * @var OrderAddressData|null
     */
    public ?OrderAddressData $address;


    protected OrderAmountData $orderAmount;

    public function getOrderAmount() : OrderAmountData
    {
        return $this->orderAmount;
    }

    public function setOrderAmount(OrderAmountData $orderAmount) : OrderData
    {
        $this->orderAmount = $orderAmount;
        return $this;
    }



}
