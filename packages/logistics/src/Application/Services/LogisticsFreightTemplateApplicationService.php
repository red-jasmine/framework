<?php

namespace RedJasmine\Logistics\Application\Services;

use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateRepositoryInterface;
use RedJasmine\Logistics\Domain\Transformers\LogisticsFreightTemplateTransformer;
use RedJasmine\Support\Application\ApplicationService;

class LogisticsFreightTemplateApplicationService extends ApplicationService
{

    public function __construct(
        public LogisticsFreightTemplateRepositoryInterface $repository,
        public LogisticsFreightTemplateTransformer $transformer,
    ) {
    }

    protected static string $modelClass = LogisticsFreightTemplate::class;

}
