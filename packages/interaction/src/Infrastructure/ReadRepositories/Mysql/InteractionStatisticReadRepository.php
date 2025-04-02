<?php

namespace RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class InteractionStatisticReadRepository extends QueryBuilderReadRepository implements InteractionStatisticReadRepositoryInterface
{


    public $modelClass = InteractionStatistic::class;


}