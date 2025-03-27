<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductSeriesReadRepository extends QueryBuilderReadRepository implements ProductSeriesReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductSeries::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),

        ];
    }

    public function allowedIncludes() : array
    {
        return [ 'products' ];
    }


}
