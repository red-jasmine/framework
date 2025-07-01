<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class ProductSkuReadRepository extends QueryBuilderReadRepository implements ProductSkuReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductSku::class;

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findList(array $ids)
    {
       return $this->query()->whereIn('id', $ids)->get();
    }


    public function allowedSorts() : array
    {
        return [
            AllowedSort::field('stock'),
            AllowedSort::field('safety_stock'),
            AllowedSort::field('sales'),
        ];
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('sku_id', 'id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('safety_stock'),
            AllowedFilter::exact('status'),
        ];
    }

    public function allowedIncludes() : array
    {
        return [
            'product'
        ];
    }


}
