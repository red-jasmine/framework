<?php

namespace RedJasmine\Card\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CardGroupBindProductReadRepository extends QueryBuilderReadRepository implements CardGroupBindProductReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CardGroupBindProduct::class;

    public function allowedIncludes() : array
    {
        return ['group','product'];
    }


    public function allowedFilters() : array
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

}
