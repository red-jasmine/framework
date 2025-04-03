<?php

namespace RedJasmine\Interaction\Application\Services\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{

    public string $resourceId;

    public string $resourceType;

    public string $interactionType;

    public ?string $userId;

    public ?string $userType;


    public ?array $other;


}