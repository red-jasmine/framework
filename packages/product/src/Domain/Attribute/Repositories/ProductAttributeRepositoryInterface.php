<?php

namespace RedJasmine\Product\Domain\Attribute\Repositories;

use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品属性仓库接口
 *
 * 提供商品属性实体的读写操作统一接口
 */
interface ProductAttributeRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找属性
     *
     * @param string $name
     *
     * @return ProductAttribute
     */
    public function findByName(string $name);

    /**
     * 根据ID数组查找属性列表
     */
    public function findByIds(array $ids);
}
