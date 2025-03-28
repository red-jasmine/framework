<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductTagReadRepository extends QueryBuilderReadRepository implements ProductTagReadRepositoryInterface
{


    /**
     * @template  T
     * @var class-string<T> $modelClass
     */
    protected static string $modelClass = ProductTag::class;

    public function findByName($name) : ?ProductTag
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
            'is_public',
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
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
        ];
    }
}
