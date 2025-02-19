<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;

class VipProductRepository extends EloquentRepository implements VipProductRepositoryInterface
{
    protected static string $eloquentModelClass = VipProduct::class;
}