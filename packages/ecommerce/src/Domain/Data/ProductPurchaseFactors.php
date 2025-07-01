<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 商品价格因子
 */
class ProductPurchaseFactors extends Data
{

    /**
     * 市场
     * @var string
     */
    public string $market = 'default';

    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;


    public ProductIdentity $product;


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


    // 定制信息
    // 国家、区域
    // 货币
    // 时间
    // 会员

}