<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品分类仓库接口
 *
 * 提供商品分类实体的读写操作统一接口
 */
interface ProductCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?ProductCategory;

    /**
     * 获取树形结构
     * 合并了原ProductCategoryReadRepositoryInterface中tree方法
     */
    public function tree(Query $query) : array;
}
