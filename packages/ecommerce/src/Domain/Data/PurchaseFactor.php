<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 购买因子
 */
class PurchaseFactor extends Data
{

    /**
     * 市场标识
     * @var string
     */
    public string $market = 'default';


    /**
     * 买家
     * @var UserInterface|null
     */
    public ?UserInterface $buyer;


    /**
     * 渠道
     * @var UserInterface|null
     */
    public ?UserInterface $channel;

    /**
     * 门店
     * @var UserInterface|null
     */
    public ?UserInterface $store;

    /**
     * 导购
     * @var UserInterface|null
     */
    public ?UserInterface $guide;

    // 国家
    public ?string $country;

    // 货币
    public ?string $currency;

    // 选择的配送方式
    public ?ShippingTypeEnum $deliveryMethod;

}