<?php

namespace RedJasmine\Vip\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Vip\Domain\Models\Vip;

/**
 * VIP仓库接口
 *
 * 提供VIP实体的读写操作统一接口
 *
 * @method Vip find($id)
 */
interface VipRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据业务类型和VIP类型查找
     */
    public function findVipType(string $biz, string $type): ?Vip;
}