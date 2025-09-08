<?php

namespace RedJasmine\Logistics\Infrastructure\Repositories;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * @method LogisticsCompany find($id)
 */
class LogisticsCompanyRepository extends Repository implements LogisticsCompanyRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = LogisticsCompany::class;

}
