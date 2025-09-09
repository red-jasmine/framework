<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品库存日志仓库接口
 *
 * 提供商品库存日志实体的读写操作统一接口
 */
interface ProductStockLogRepositoryInterface extends RepositoryInterface
{
    // 合并了原ProductStockLogReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
