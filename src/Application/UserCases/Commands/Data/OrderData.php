<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

class OrderData extends Data
{
    /**
     * 卖家
     * @var UserData
     */
    public UserData $seller;
    /**
     * 买家
     * @var UserData
     */
    public UserData $buyer;
    /**
     * 订单类型
     * @var OrderTypeEnum
     */
    public OrderTypeEnum $orderType;
    /**
     * 支付方式
     * @var PayTypeEnum
     */
    public PayTypeEnum $payType = PayTypeEnum::ONLINE;
    /**
     * 渠道
     * @var UserData|null
     */
    public ?UserData $channel = null;
    /**
     * 门店
     * @var UserData|null
     */
    public ?UserData $store = null;
    /**
     * 导购
     * @var UserData|null
     */
    public ?UserData $guide = null;
    /**
     * 订单标题
     * @var string
     */
    public string $title;
    /**
     * 客户端类型
     * @var string|null
     */
    public ?string $clientType         = null;
    public ?string $clientVersion      = null;
    public ?string $clientIp           = null;
    public ?string $sourceType         = null;
    public ?string $sourceId           = null;
    public ?string $outerOrderId       = null;
    public ?string $sellerCustomStatus = null;
    public ?string $contact            = null;
    public ?string $password           = null;
    public ?string $sellerRemarks      = null;
    public ?string $sellerMessage      = null;
    public ?string $buyerRemarks       = null;
    public ?string $buyerMessage       = null;
    public ?array  $sellerExpands      = null;
    public ?array  $buyerExpands       = null;
    public ?array  $otherExpands       = null;
    public ?array  $tools              = null;
    public Amount  $freightAmount;
    public Amount  $discountAmount;
    /**
     * 商品集合
     * @var Collection<OrderProductData>
     */
    public Collection $products;
    /**
     * 地址
     * @var OrderAddressData|null
     */
    public ?OrderAddressData $address;

    public function __construct()
    {
        $this->discountAmount = new Amount(0);
        $this->freightAmount  = new Amount(0);
    }


}
