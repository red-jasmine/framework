<?php

namespace RedJasmine\Vip\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Vip\Domain\Models\VipProduct;

/**
 * VIP产品仓库接口
 *
 * 提供VIP产品实体的读写操作统一接口
 *
 * @method VipProduct find($id)
 */
interface VipProductRepositoryInterface extends RepositoryInterface
{

}