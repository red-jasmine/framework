<?php

namespace RedJasmine\Interaction\Domain\Repositories;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface InteractionStatisticRepositoryInterface extends RepositoryInterface
{
    public function increment(string $resourceType, string $resourceId, string $interactionType, int $quantity = 1);

    public function findByResource(string $resourceType, string $resourceId, string $interactionType) : ?InteractionStatistic;

}