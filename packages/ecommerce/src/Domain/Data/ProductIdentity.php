<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 商品身份信息
 */
class ProductIdentity extends Data
{

    /**
     * 卖家
     * @var UserInterface|null
     */
    public ?UserInterface $seller;
    /**
     * 产品源类型
     * @var string
     */
    public string $type;
    /**
     * 产品ID
     * @var string
     */
    public string $id;
    /**
     * 规格ID
     * @var string
     */
    public string $skuId;
}