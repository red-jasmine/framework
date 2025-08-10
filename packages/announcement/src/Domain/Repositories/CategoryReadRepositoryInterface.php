<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface CategoryReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 获取分类树
     */
    public function tree(?Query $query = null): array;
}
