<?php

namespace RedJasmine\Product\Domain\Tag\Repositories;

use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品标签仓库接口
 *
 * 提供商品标签实体的读写操作统一接口
 */
interface ProductTagRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找标签
     */
    public function findByName($name) : ?ProductTag;
}
