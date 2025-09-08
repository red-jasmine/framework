<?php

namespace RedJasmine\Logistics\Infrastructure\Repositories;


use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * @method LogisticsFreightTemplate find($id)
 */
class LogisticsFreightTemplateRepository extends Repository implements LogisticsFreightTemplateRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = LogisticsFreightTemplate::class;

}
