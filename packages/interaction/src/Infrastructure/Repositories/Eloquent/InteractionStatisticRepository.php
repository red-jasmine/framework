<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories\Eloquent;

use RedJasmine\Interaction\Domain\Models\Enums\InteractionTypeEnum;
use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class InteractionStatisticRepository extends EloquentRepository implements InteractionStatisticRepositoryInterface
{

    protected static string $eloquentModelClass = InteractionStatistic::class;

    public function findByResource(string $resourceType, string $resourceId, InteractionTypeEnum $interactionType) : ?InteractionStatistic
    {
        return static::$eloquentModelClass::where([
            'resource_type'    => $resourceType,
            'resource_id'      => $resourceId,
            'interaction_type' => $interactionType->value
        ])->first();
    }


}