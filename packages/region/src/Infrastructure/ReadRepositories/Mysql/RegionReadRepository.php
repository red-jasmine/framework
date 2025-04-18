<?php

namespace RedJasmine\Region\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class RegionReadRepository extends QueryBuilderReadRepository implements RegionReadRepositoryInterface
{
    use HasTree;

    public static string $modelClass = Region::class;

    public function tree(?Query $query = null) : array
    {
        $nodes = $this->query($query)
                      ->select($this->baseFields())
                      ->get()->toArray();
        $model = (new static::$modelClass);

        return $model->toTree($nodes);
    }

    protected function baseFields() : array
    {
        return [
            'code',
            'parent_code',
            'name',
            'level'
        ];
    }

    public function children(?Query $query) : array
    {

        return $this->query($query)
                    ->select($this->baseFields())
                    ->get()->toArray();


    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('country_code'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('parent_code'),
            AllowedFilter::exact('level'),
        ];
    }
}