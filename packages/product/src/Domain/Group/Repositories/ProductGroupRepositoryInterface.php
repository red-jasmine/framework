<?php

namespace RedJasmine\Product\Domain\Group\Repositories;

use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品分组仓库接口
 *
 * 提供商品分组实体的读写操作统一接口
 */
interface ProductGroupRepositoryInterface extends RepositoryInterface
{
    /**
     * 获取树形结构
     * 合并了原ProductGroupReadRepositoryInterface中tree方法
     */
    public function tree(Query $query) : array;

    /**
     * 根据名称查找分组
     * 合并了原ProductGroupReadRepositoryInterface中findByName方法
     */
    public function findByName($name) : ?ProductGroup;
}
