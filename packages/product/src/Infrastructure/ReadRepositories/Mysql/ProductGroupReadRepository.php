<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupReadRepositoryInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductGroupReadRepository extends QueryBuilderReadRepository implements ProductGroupReadRepositoryInterface
{
    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductGroup::class;


    public function findByName($name) : ?ProductGroup
    {
        return $this->query()->where('name', $name)->first();
    }


    public function allowedFields() : array
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
            'extra',
        ];

    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),
        ];
    }


}
