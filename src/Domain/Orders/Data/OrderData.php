<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use Illuminate\Support\Collection;
use RedJasmine\Order\Application\UserCases\Commands\Data\OrderAddressData;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Data\UserData;

class OrderData extends Command
{


    /**
     * 买家
     * @var UserData
     */
    public UserData $buyer;

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
     * @var string|null
     */
    public ?string $title  = null;
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


    /**
     * 地址
     * @var OrderAddressData|null
     */
    public ?OrderAddressData $address;


    /**
     * @var Collection<ProductData>
     */
    public Collection $products;


    protected UserData $seller;

    public function getSeller() : UserData
    {
        return $this->seller;
    }

    public function setSeller(UserData $seller) : OrderData
    {
        $this->seller = $seller;
        return $this;
    }


}
