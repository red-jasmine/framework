<?php

namespace RedJasmine\Product\Domain\Price\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 商品价格查询数据
 */
class ProductPriceData extends FindQuery
{
    /**
     * 产品ID
     * @var int
     */
    public int $productId;

    /**
     * 规格ID（必填，所有价格都挂在变体上）
     * @var int
     */
    public int $skuId;

    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;

    /**
     * 市场
     * @var string
     */
    public string $market = '*';

    /**
     * 门店
     * @var string
     */
    public string $store = '*';

    /**
     * 用户等级
     * @var string
     */
    public string $userLevel = 'default';

    /**
     * 买家
     * @var UserInterface|null
     */
    public ?UserInterface $buyer = null;

    /**
     * 渠道
     * @var UserInterface|null
     */
    public ?UserInterface $channel = null;

    /**
     * 导购
     * @var UserInterface|null
     */
    public ?UserInterface $guide = null;

    /**
     * 国家
     * @var string|null
     */
    public ?string $country = null;
}
