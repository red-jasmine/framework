<?php

namespace RedJasmine\Logistics\Infrastructure\Repositories;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * @method LogisticsCompany find($id)
 */
class LogisticsCompanyRepository extends EloquentRepository implements LogisticsCompanyRepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = LogisticsCompany::class;

}
