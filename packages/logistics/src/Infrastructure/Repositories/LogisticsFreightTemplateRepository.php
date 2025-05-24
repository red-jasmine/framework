<?php

namespace RedJasmine\Logistics\Infrastructure\Repositories;


use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * @method LogisticsFreightTemplate find($id)
 */
class LogisticsFreightTemplateRepository extends EloquentRepository implements LogisticsFreightTemplateRepositoryInterface
{

    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = LogisticsFreightTemplate::class;

}
