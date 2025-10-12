<?php

namespace RedJasmine\Product\Domain\Attribute\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品属性组仓库接口
 *
 * 提供商品属性组实体的读写操作统一接口
 */
interface ProductAttributeGroupRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找属性组
     */
    public function findByName(string $name);
}
