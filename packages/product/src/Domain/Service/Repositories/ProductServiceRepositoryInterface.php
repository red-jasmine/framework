<?php

namespace RedJasmine\Product\Domain\Service\Repositories;

use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品服务仓库接口
 *
 * 提供商品服务实体的读写操作统一接口
 */
interface ProductServiceRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找服务
     */
    public function findByName($name) : ?ProductService;
}
