<?php

namespace RedJasmine\Product\Domain\Brand\Repositories;

use RedJasmine\Product\Domain\Brand\Models\ProductBrand;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 品牌仓库接口
 *
 * 提供品牌实体的读写操作统一接口
 */
interface BrandRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找品牌
     */
    public function findByName($name) : ?ProductBrand;


    // 所有读写操作都通过统一接口提供
}
