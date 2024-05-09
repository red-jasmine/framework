<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Data;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;

class OrderData extends Data
{
    public function __construct()
    {
        $this->discountAmount = new Amount(0);
        $this->freightAmount  = new Amount(0);
    }


    public static function morphs() : array
    {
        return [ 'seller', 'buyer', 'channel', 'store', 'guide' ];
    }

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
     * @var \RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum
     */
    public OrderTypeEnum $orderType;

    /**
     * 支付方式
     * @var PayTypeEnum
     */
    public PayTypeEnum $payType = PayTypeEnum::ONLINE;


    public string  $title;
    public ?string $outerOrderId       = null;
    public ?string $sellerCustomStatus = null;

    public Amount $freightAmount;

    public Amount $discountAmount;


    public ?UserData $channel    = null;
    public ?UserData $store      = null;
    public ?UserData $guide      = null;
    public ?string   $clientType;
    public ?string   $clientVersion;
    public ?string   $clientIp;
    public ?string   $sourceType = null;
    public ?string   $sourceId   = null;
    // 虚拟商品 通知方
    public ?string $contact  = null;
    public ?string $password = null;


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
    /**
     * 其他信息
     * @var OrderInfoData|null
     */
    public ?OrderInfoData $info;

}
