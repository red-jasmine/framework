<?php

namespace RedJasmine\Product\Domain\Attribute\Repositories;

use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品属性值仓库接口
 *
 * 提供商品属性值实体的读写操作统一接口
 */
interface ProductAttributeValueRepositoryInterface extends RepositoryInterface
{
    /**
     * 在指定属性中根据名称查找属性值
     *
     * @param int    $pid
     * @param string $name
     *
     * @return ProductAttributeValue
     */
    public function findByNameInAttribute(int $pid, string $name);

    /**
     * 在指定属性中根据ID数组查找属性值列表
     */
    public function findByIdsInAttribute(int $pid, array $ids);
}
