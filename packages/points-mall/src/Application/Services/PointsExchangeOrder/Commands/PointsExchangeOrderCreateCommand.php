<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 兑换订单创建命令
 */
class PointsExchangeOrderCreateCommand extends Data
{
    public UserInterface $user;

    /**
     * 地址ID
     * @var int|null
     */
    public ?int $addressId = null;

    public int $pointsProductId;

    public ?string $productSkuId = null;
    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;

}