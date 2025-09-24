<?php

namespace RedJasmine\Order\Domain\Repositories;

use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 订单卡密仓库接口
 *
 * 提供订单卡密实体的读写操作统一接口
 *
 * @method OrderCardKey find($id)
 */
interface OrderCardKeyRepositoryInterface extends RepositoryInterface
{

}
