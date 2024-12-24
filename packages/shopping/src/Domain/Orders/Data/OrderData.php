<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class OrderData extends Data
{


    /**
     * 买家
     * @var UserInterface
     */
    public UserInterface $buyer;

    protected UserInterface $seller;

    /**
     * 渠道
     * @var UserInterface|null
     */
    public ?UserInterface $channel = null;
    /**
     * 门店
     * @var UserInterface|null
     */
    public ?UserInterface $store = null;
    /**
     * 导购
     * @var UserInterface|null
     */
    public ?UserInterface $guide = null;

    /**
     * 订单标题
     * @var string|null
     */
    public ?string $title = null;
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


    public function getSeller() : UserInterface
    {
        return $this->seller;
    }

    public function setSeller(UserInterface $seller) : OrderData
    {
        $this->seller = $seller;
        return $this;
    }


}
