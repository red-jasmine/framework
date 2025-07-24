<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PointProductCategoryReadRepositoryInterface extends ReadRepositoryInterface
{
    public function tree(Query $query): array;
} 