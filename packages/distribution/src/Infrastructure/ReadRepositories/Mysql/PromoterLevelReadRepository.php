<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterLevelReadRepository extends QueryBuilderReadRepository implements PromoterLevelReadRepositoryInterface
{
    protected static string $modelClass = PromoterLevel::class;

    public function findLevel(int $level) : PromoterLevel
    {
        return $this->query()->where('level', $level)->firstOrFail();
    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('level'),
            AllowedFilter::exact('status'),
        ];
    }
}