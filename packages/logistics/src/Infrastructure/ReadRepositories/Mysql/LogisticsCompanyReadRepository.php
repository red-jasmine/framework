<?php

namespace RedJasmine\Logistics\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyReadRepositoryInterface;
use RedJasmine\Logistics\Infrastructure\ReadRepositories\Mo;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class LogisticsCompanyReadRepository extends QueryBuilderReadRepository implements LogisticsCompanyReadRepositoryInterface
{

    public $modelClass = LogisticsCompany::class;

}
