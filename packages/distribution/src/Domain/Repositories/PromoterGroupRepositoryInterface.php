<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\BaseRepositoryInterface;

/**
 * 推广员分组仓库接口
 *
 * 提供推广员分组实体的读写操作统一接口
 *
 * @method PromoterGroup find($id)
 */
interface PromoterGroupRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 获取分组树形结构
     */
    public function tree(Query $query) : array;
}
