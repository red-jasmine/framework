<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class ProductReadRepository extends QueryBuilderReadRepository implements ProductReadRepositoryInterface
{

    public static $modelClass = Product::class;

    /**
     * @param  array  $ids
     *
     * @return Product[]
     */
    public function findList(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    public function findSkuById(int $skuId)
    {
        return $this->query()->findOrFail($skuId);
    }


    public function allowedSorts() : array
    {
        return [
            AllowedSort::field('price'),
            AllowedSort::field('cost_price'),
            AllowedSort::field('market_price'),
            AllowedSort::field('sales'),
            AllowedSort::field('stock'),
            AllowedSort::field('on_sale_time'),
            AllowedSort::field('modified_time'),
        ];
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('market'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('shipping_type'),
            AllowedFilter::exact('outer_id'),
            AllowedFilter::exact('is_multiple_spec'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('product_group_id'),
            AllowedFilter::exact('barcode'),
            AllowedFilter::exact('product_model'),
        ];
    }


    public function allowedIncludes() : array
    {
        return [
            'extension',
            'services',
            'skus',
            'brand',
            'category',
            'productGroups',
            'extendProductGroups',
            'series',
            'tags',
        ];
    }


}
