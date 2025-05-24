<?php

namespace RedJasmine\Logistics\Application\Services;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyReadRepositoryInterface;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class LogisticsCompanyApplicationService extends ApplicationService
{

    public function __construct(
        public LogisticsCompanyRepositoryInterface $repository,
        public LogisticsCompanyReadRepositoryInterface $readRepository,
    ) {
    }

    protected static string $modelClass = LogisticsCompany::class;

}
