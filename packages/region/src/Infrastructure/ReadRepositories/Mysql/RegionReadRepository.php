<?php

namespace RedJasmine\Region\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class RegionReadRepository extends QueryBuilderReadRepository implements RegionReadRepositoryInterface
{
    use HasTree;

    protected mixed      $defaultSort = '-code';
    public static string $modelClass  = Region::class;

    public function tree(?Query $query = null) : array
    {
        $nodes = $this->queryBuilder($query)->select($this->baseFields())->get();
        $model = (new static::$modelClass);
        return $model->toTree($nodes);
    }


    public function allowedSorts() : array
    {
        return ['code'];
    }

    protected function baseFields() : array
    {
        return [
            'code',
            'parent_code',
            'name',
            'type',
            'level'
        ];
    }

    public function children(?Query $query) : array
    {

        return $this->queryBuilder($query)
                    ->select($this->baseFields())
                    ->get()->toArray();


    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('country_code'),
            AllowedFilter::exact('parent_code'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('type'),
            AllowedFilter::scope('level'),
        ];
    }
}