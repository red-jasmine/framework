<?php

namespace RedJasmine\Order\Domain\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class OrderData extends Data
{

    public string $appId = 'system';


    public string $currency = 'CNY';

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
     * 订单标题
     * @var string
     */
    public string $title;


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
    public ?array  $sellerExtra       = null;
    public ?array  $buyerExtra        = null;
    public ?array  $otherExtra        = null;
    public ?array  $form               = null;
    public ?array    $tools          = null;
    public ?MoneyOld $freightAmount  = null;
    public ?MoneyOld $discountAmount = null;
    public ?string   $clientType     = null;
    public ?string $clientVersion      = null;
    public ?string $clientIp           = null;

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
     * 等待接单自动确认时间
     * 单位： 分钟
     * 当为 0 时 是付款成功后 立即确认
     * 当 为 -1 时  无限等待
     * @var int
     */
    public int $waitAcceptMaxTime = 0;


    // 自定处理流程控制
    /**
     * @var int
     */
    public int $paymentWaitMaxTime = -1;
    /**
     * @var int
     */
    public int $acceptWaitMaxTime = -1;
    /**
     * @var int
     */
    public int $confirmWaitMaxTime = -1;
    /**
     * @var int
     */
    public int $rateWaitMaxTime = -1;


    public function __construct()
    {
        $this->discountAmount = MoneyOld::make();
        $this->freightAmount  = MoneyOld::make();
    }


}
