<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 互动统计仓库实现
 *
 * 基于Repository实现，提供互动统计实体的读写操作能力
 */
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

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('resource_type'),
            AllowedFilter::exact('resource_id'),
            AllowedFilter::exact('interaction_type'),
        ];
    }
}
