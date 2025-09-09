<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 积分商品分类仓库接口
 *
 * 提供积分商品分类实体的读写操作统一接口
 *
 * @method PointsProductCategory find($id)
 */
interface PointProductCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找分类
     */
    public function findByName($name): ?PointsProductCategory;

    /**
     * 获取树形结构分类
     */
    public function tree(Query $query): array;
} 