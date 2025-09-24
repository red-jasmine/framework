<?php

namespace RedJasmine\Interaction\Domain\Repositories;

use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 互动统计仓库接口
 *
 * 提供互动统计实体的读写操作统一接口
 */
interface InteractionStatisticRepositoryInterface extends RepositoryInterface
{
    public function increment(string $resourceType, string $resourceId, string $interactionType, int $quantity = 1) : int;

    public function decrement(string $resourceType, string $resourceId, string $interactionType, int $quantity = 1) : int;

    public function findByResource(string $resourceType, string $resourceId, string $interactionType) : ?InteractionStatistic;


}
