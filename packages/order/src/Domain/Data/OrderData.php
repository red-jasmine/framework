<?php

namespace RedJasmine\Order\Domain\Data;

use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class OrderData extends Data
{

    public string $appId = 'system';
    // 订单类型
    public string $orderType;
    /**
     * 卖家
     * @var UserInterface
     */
    public UserInterface $seller;

    /**
     * 货币
     * @var Currency
     */
    public Currency $currency;
    /**
     * 买家
     * @var UserInterface
     */
    public UserInterface $buyer;

    /**
     * 发货类型
     * @var ShippingTypeEnum
     */
    #[WithCast(EnumCast::class, type: ShippingTypeEnum::class)]
    public ShippingTypeEnum $shippingType;
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

    /**
     * 整体邮费
     * @var Money|null
     */
    public ?Money $freightAmount = null;

    /**
     * 订单级别优惠
     * @var Money|null
     */
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

    public int $acceptTimeout = 0; // 0 自动接单

    public int $confirmTimeout = -1; // -1 不指定确认 、 0 自动确认 > 0 超过某个时间后自动确认

    public int $rateTimeout = -1;


}
