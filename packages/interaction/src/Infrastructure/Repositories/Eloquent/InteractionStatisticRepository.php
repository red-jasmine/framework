<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories\Eloquent;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class InteractionStatisticRepository extends Repository implements InteractionStatisticRepositoryInterface
{

    protected static string $modelClass = InteractionStatistic::class;

    public function findByResource(string $resourceType, string $resourceId, string $interactionType) : ?InteractionStatistic
    {
        return static::$modelClass::where([
            'resource_type'    => $resourceType,
            'resource_id'      => $resourceId,
            'interaction_type' => $interactionType
        ])->first();
    }

    public function increment(string $resourceType, string $resourceId, string $interactionType, int $quantity = 1) : int
    {
        $result = InteractionStatistic::where([
            'resource_type'    => $resourceType,
            'resource_id'      => $resourceId,
            'interaction_type' => $interactionType
        ])->increment('quantity', $quantity);
        if ($result === 0) {
            InteractionStatistic::make([
                'resource_type'    => $resourceType,
                'resource_id'      => $resourceId,
                'interaction_type' => $interactionType,
                'quantity'         => $quantity,
            ])->save();
        }

        return 1;
    }

    public function decrement(string $resourceType, string $resourceId, string $interactionType, int $quantity = 1) : int
    {
        return InteractionStatistic::where([
            'resource_type'    => $resourceType,
            'resource_id'      => $resourceId,
            'interaction_type' => $interactionType
        ])->decrement('quantity', $quantity);
    }


}