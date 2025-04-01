<?php

namespace RedJasmine\Interaction\Domain\Repositories;

use RedJasmine\Interaction\Domain\Models\Enums\InteractionTypeEnum;
use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface InteractionStatisticRepositoryInterface extends RepositoryInterface
{


    public function findByResource(string $resourceType, string $resourceId, InteractionTypeEnum $interactionType) : ?InteractionStatistic;

}