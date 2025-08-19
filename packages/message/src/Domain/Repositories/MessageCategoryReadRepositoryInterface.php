<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息分类只读仓库接口
 */
interface MessageCategoryReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 获取分类树
     */
    public function tree(?Query $query = null): array;
}
