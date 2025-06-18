<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterTeamReadRepository extends QueryBuilderReadRepository implements PromoterTeamReadRepositoryInterface
{
    protected static string $modelClass = PromoterTeam::class;

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('promoter_id'),
            AllowedFilter::exact('team_name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('parent_id'),
        ];
    }
}