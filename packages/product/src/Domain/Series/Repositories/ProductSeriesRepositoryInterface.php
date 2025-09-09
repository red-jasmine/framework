<?php

namespace RedJasmine\Product\Domain\Series\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品系列仓库接口
 *
 * 提供商品系列实体的读写操作统一接口
 */
interface ProductSeriesRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据商品ID查找商品系列
     * 合并了原ProductSeriesReadRepositoryInterface中findProductSeries方法
     *
     * @param $productId
     * @return ProductSeries
     * @throws ModelNotFoundException
     */
    public function findProductSeries($productId) : ProductSeries;
}
