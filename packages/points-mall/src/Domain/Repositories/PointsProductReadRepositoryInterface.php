<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PointsProductReadRepositoryInterface extends ReadRepositoryInterface
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
    public function findByProductSource(string $productType, string $productId): ?\RedJasmine\PointsMall\Domain\Models\PointsProduct;

    /**
     * 查找用户的商品
     */
    public function findByOwner(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 查找商品及其分类
     */
    public function findWithCategory(string $productId): ?\RedJasmine\PointsMall\Domain\Models\PointsProduct;

    /**
     * 按分类查找上架商品
     */
    public function findOnSaleByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection;
} 