<?php

namespace RedJasmine\Order\Domain\Data;

use Cknow\Money\Money;
use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class OrderData extends Data
{

    public string $appId = 'system';

    /**
     * 卖家
     * @var UserInterface
     */
    public UserInterface $seller;
    /**
     * 买家
     * @var UserInterface
     */
    public UserInterface $buyer;
    /**
     * 订单类型
     * @var OrderTypeEnum
     */
    #[WithCast(EnumCast::class, type: OrderTypeEnum::class)]
    public OrderTypeEnum $orderType;

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
     * 来源
     * @var UserInterface|null
     */
    public ?UserInterface $source = null;


    public ?Money $freightAmount  = null;
    public ?Money $discountAmount = null;
    /**
     * 商品集合
     * @var array<OrderProductData>
     */
    public array $products;

    public ?string $title              = null;
    public ?string $outerOrderId       = null;
    public ?string $sellerCustomStatus = null;
    public ?string $sellerRemarks      = null;
    public ?string $sellerMessage      = null;
    public ?string $buyerRemarks       = null;
    public ?string $buyerMessage       = null;
    public ?array  $sellerExtra        = null;
    public ?array  $buyerExtra         = null;
    public ?array  $otherExtra         = null;
    public ?array  $form               = null;
    public ?array  $tools              = null;
    public ?string $clientType         = null;
    public ?string $clientVersion      = null;
    public ?string $clientIp           = null;


    /**
     * 地址
     * @var OrderAddressData|null
     */
    public ?OrderAddressData $address;


    // 自定处理流程控制
    /**
     * @var int
     */

    public int $paymentTimeout = -1;

    public int $acceptTimeout = -1;

    public int $confirmTimeout = -1;

    public int $rateTimeout = -1;


}
