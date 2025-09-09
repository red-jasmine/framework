<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 积分商品仓库接口
 *
 * 提供积分商品实体的读写操作统一接口
 *
 * @method PointsProduct find($id)
 */
interface PointsProductRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找上架商品
     */
    public function findOnSale(): \Illuminate\Database\Eloquent\Collection;
    
    /**
     * 根据分类查找商品
     */
    public function findByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection;
    
    /**
     * 根据商品源查找商品
     */
    public function findByProductSource(string $productType, string $productId): ?PointsProduct;
} 