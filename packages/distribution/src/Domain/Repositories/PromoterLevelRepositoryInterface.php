<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 推广员等级仓库接口
 *
 * 提供推广员等级实体的读写操作统一接口
 *
 * @method PromoterLevel find($id)
 */
interface PromoterLevelRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据等级查找推广员等级
     */
    public function findLevel(int $level) : PromoterLevel;
}
