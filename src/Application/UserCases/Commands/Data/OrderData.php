<?php

namespace RedJasmine\Shopping\Application\UserCases\Commands\Data;

use Illuminate\Support\Collection;
use RedJasmine\Order\Application\UserCases\Commands\Data\OrderAddressData;
use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Data\UserData;

class OrderData extends Command
{

    /**
     * 买家
     * @var UserData
     */
    public UserData $buyer;

    public ?string $outerOrderId = null;


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
     * 地址
     * @var OrderAddressData|null
     */
    public ?OrderAddressData $address;


    public ?string $buyerRemarks;
    public ?string $buyerMessage;
    public ?array  $buyerExpands;
    public ?array  $tools;


    /**
     * @var Collection<ProductData>
     */
    public Collection $products;

}
