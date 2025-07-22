<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PointsProductRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据ID查找积分商品
     */
    public function find($id): ?PointsProduct;


    /**
     * 减少库存
     */
    public function decreaseStock(string $productId, int $quantity): bool;

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