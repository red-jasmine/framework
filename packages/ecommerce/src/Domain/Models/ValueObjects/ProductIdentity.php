<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 商品身份信息
 */
class ProductIdentity extends Data
{

    /**
     * 买家
     * @var UserInterface|null
     */
    public ?UserInterface $seller;
    /**
     * 产品源类型
     * @var string
     */
    public string $productType;
    /**
     * 产品ID
     * @var string
     */
    public string $productId;
    /**
     * 规格ID
     * @var string
     */
    public string $skuId;
}