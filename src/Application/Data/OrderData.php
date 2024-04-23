<?php

namespace RedJasmine\Order\Application\Data;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Enums\ShippingTypeEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

class OrderData extends Data
{
    // 来自模型的 转换
    // 这个是可以传输 DTO 需要对 聚合根进行转换 比创建 有更多的属性 TODO
    public static function morphs() : array
    {
        return [ 'seller', 'buyer', 'channel', 'store', 'guide' ];
    }

    public ?int             $id;
    public string           $title;
    public UserData         $seller;
    public UserData         $buyer;
    public ShippingTypeEnum $shippingType;
    public OrderTypeEnum    $orderType;
    public PayTypeEnum      $payType            = PayTypeEnum::ONLINE;
    public ?string          $outerOrderId       = null;
    public ?string          $sellerCustomStatus = null;
    public ?UserData        $channel            = null;
    public ?UserData        $store              = null;
    public ?UserData        $guide              = null;
    public string|float|int $freightAmount      = 0;
    public string|float|int $discountAmount     = 0;


    // 虚拟商品 通知方
    public ?string $contact  = null;
    public ?string $password = null;

    public ?string $clientType;
    public ?string $clientVersion;
    public ?string $clientIp;

    public ?string $sourceType = null;
    public ?string $sourceId   = null;

    /** @var Collection<OrderProductData> */
    public Collection $products;

    public ?OrderAddressData $address;


    public ?OrderInfoData $info;


    public ?string $payableAmount;

}
