<?php

namespace RedJasmine\Product\Domain\Product\Repositories;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品仓库接口
 *
 * 提供商品实体的读写操作统一接口
 *
 * @method Product find($id)
 */
interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据ID数组查找商品列表
     */
    public function findList(array $ids);

    /**
     * 根据SKU ID查找商品
     */
    public function findSkuById(int $skuId);
}
