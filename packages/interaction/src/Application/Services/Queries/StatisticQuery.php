<?php

namespace RedJasmine\Interaction\Application\Services\Queries;

class StatisticQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{

    public string $resourceId;

    public string $resourceType;

    public ?string $interactionType;

}