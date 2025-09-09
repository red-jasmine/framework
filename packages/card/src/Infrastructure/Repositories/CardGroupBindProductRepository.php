<?php

namespace RedJasmine\Card\Infrastructure\Repositories;

use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 卡密分组绑定商品仓库实现
 *
 * 基于Repository实现，提供卡密分组绑定商品实体的读写操作能力
 */
class CardGroupBindProductRepository extends Repository implements CardGroupBindProductRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = CardGroupBindProduct::class;

    /**
     * 根据商品信息查找绑定记录
     */
    public function findByProduct(UserInterface $owner, string $productType, int $productId, int $skuId) : ?CardGroupBindProduct
    {
        return static::$modelClass::query()->onlyOwner($owner)
                                          ->where('product_type', $productType)
                                          ->where('product_id', $productId)
                                          ->where('sku_id', $skuId)
                                          ->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('sku_id'),
            AllowedFilter::exact('group_id'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return ['group','product'];
    }
}
