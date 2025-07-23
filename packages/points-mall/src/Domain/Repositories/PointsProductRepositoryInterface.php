<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PointsProductRepositoryInterface extends RepositoryInterface
{
    public function find($id): ?PointsProduct;
    public function store(PointsProduct $model): PointsProduct;
    public function update(PointsProduct $model): PointsProduct;
    public function delete(PointsProduct $model): bool;
} 