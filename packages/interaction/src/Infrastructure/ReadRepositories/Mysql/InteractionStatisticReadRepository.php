<?php

namespace RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class InteractionStatisticReadRepository extends QueryBuilderReadRepository implements InteractionStatisticReadRepositoryInterface
{


    public static string $modelClass = InteractionStatistic::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('resource_type'),
            AllowedFilter::exact('resource_id'),
            AllowedFilter::exact('interaction_type'),
        ];
    }
}