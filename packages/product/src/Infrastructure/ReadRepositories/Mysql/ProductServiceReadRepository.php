<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductServiceReadRepository extends QueryBuilderReadRepository implements ProductServiceReadRepositoryInterface
{


    /**
     * @template  T
     * @var class-string<T> $modelClass
     */
    protected static string $modelClass = ProductService::class;

    public function findByName($name) : ?ProductService
    {
        return $this->query()->where('name', $name)->first();
    }


    public function allowedFields() : array
    {
        return [
            'id',
            'name',
            'description',
            'icon',
            'cluster',
            'sort',

            'is_show',
            'status',

        ];

    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_public'),
            AllowedFilter::exact('cluster'),

        ];
    }


}
