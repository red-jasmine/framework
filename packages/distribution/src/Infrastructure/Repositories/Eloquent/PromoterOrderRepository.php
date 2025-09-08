<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterOrder;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterOrderRepository extends Repository implements PromoterOrderRepositoryInterface
{
    protected static string $modelClass = PromoterOrder::class;
}