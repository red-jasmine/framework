<?php

namespace RedJasmine\Interaction\Application\Services\Queries;

class ResourceUserFindQuery extends \RedJasmine\Support\Domain\Data\Queries\FindQuery
{
    public string $resourceId;

    public string $resourceType;

    public string $interactionType;

    public ?string $userId;

    public ?string $userType;
}