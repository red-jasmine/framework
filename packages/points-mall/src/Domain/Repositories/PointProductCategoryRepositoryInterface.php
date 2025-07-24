<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PointProductCategoryRepositoryInterface extends RepositoryInterface
{
    public function findByName($name): ?PointsProductCategory;
} 