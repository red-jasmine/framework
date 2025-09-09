<?php

namespace RedJasmine\Interaction\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 互动记录仓库接口
 *
 * 提供互动记录实体的读写操作统一接口
 */
interface InteractionRecordRepositoryInterface extends RepositoryInterface
{
    public function findByInteractionType(string $interactionType, $id);

    /**
     * 根据资源用户查找最后的记录
     * 合并了原InteractionRecordReadRepositoryInterface中的方法
     */
    public function findByResourceUserLast(FindQuery $query);
}
