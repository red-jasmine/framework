<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductCategoryReadRepository extends QueryBuilderReadRepository implements ProductCategoryReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductCategory::class;


    public function allowedFilters(?Query $query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('status'),

        ];
    }


    public function allowedFields(?Query $query = null) : array
    {
        return [
            'id',
            'parent_id',
            'name',
            'image',
            'group_name',
            'sort',
            'is_leaf',
            'is_show',
            'status',
            'extras',
        ];

    }
}
