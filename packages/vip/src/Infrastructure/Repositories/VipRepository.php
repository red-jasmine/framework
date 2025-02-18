<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipRepository extends EloquentRepository implements VipRepositoryInterface
{
    protected static string $eloquentModelClass = Vip::class;
}