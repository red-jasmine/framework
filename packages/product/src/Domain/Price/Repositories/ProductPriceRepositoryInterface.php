<?php

namespace RedJasmine\Product\Domain\Price\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品级别价格汇总表仓库接口
 */
interface ProductPriceRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据维度查找商品级别价格汇总
     *
     * @param  int  $productId  商品ID
     * @param  string  $market  市场
     * @param  string  $store  门店
     * @param  string  $userLevel  用户等级
     * @param  int  $quantity
     *
     * @return ProductPrice|null
     */
    public function findByDimensions(
        int $productId,
        string $market,
        string $store,
        string $userLevel,
        int $quantity
    ): ?ProductPrice;

    /**
     * 批量查询商品级别价格汇总
     * 
     * @param array<int> $productIds 商品ID数组
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return Collection<ProductPrice> 价格集合，key为 product_id
     */
    public function findBatchPrices(
        array $productIds,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*'
    ): Collection;

    /**
     * 根据商品ID查找所有价格汇总
     *
     * @param int $productId 商品ID
     * @return Collection<ProductPrice>
     */
    public function findByProduct(int $productId): Collection;
}
