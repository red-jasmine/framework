<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

class VipRepository extends Repository implements VipRepositoryInterface
{

    protected static string $modelClass = Vip::class;
}