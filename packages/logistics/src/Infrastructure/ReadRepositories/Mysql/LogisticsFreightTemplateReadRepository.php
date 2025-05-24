<?php

namespace RedJasmine\Logistics\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class LogisticsFreightTemplateReadRepository extends QueryBuilderReadRepository implements LogisticsFreightTemplateReadRepositoryInterface
{

    protected static string $modelClass = LogisticsFreightTemplate::class;

}
