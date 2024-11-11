<?php

namespace RedJasmine\Product\Domain\Price\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 *
 * 商品价格产生
 */
class ProductPriceData extends Data
{

    /**
     * 产品ID
     * @var int
     */
    public int $productId;

    /**
     * 规格ID
     * @var int
     */
    public int $skuId;

    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;

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

    // 国家、区域
    // 货币
    // 时间
    // 会员


}
