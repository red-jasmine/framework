<?php

namespace RedJasmine\Ecommerce\Domain\Data\Order;

use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Ecommerce\Domain\Helpers\HasSerialNumber;
use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Support\Domain\Contracts\UserInterface;

/**
 * 和订单领域 共享结构
 */
class OrderData extends PurchaseFactor
{
    use HasSerialNumber;


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
    /**
     * @var OrderProductData[]
     */
    public array $products;

    protected OrderAmountInfoData $orderAmountInfo;
    protected ?string              $orderNo;


    public function getOrderAmountInfo() : OrderAmountInfoData
    {
        return $this->orderAmountInfo;
    }

    public function setOrderAmountInfo(OrderAmountInfoData $orderAmountInfo) : OrderData
    {
        $this->orderAmountInfo = $orderAmountInfo;
        return $this;
    }

    public function getOrderNo() : ?string
    {
        return $this->orderNo ?? null;
    }

    public function setOrderNo(string $orderNo) : OrderData
    {
        $this->orderNo = $orderNo;
        return $this;
    }


}
