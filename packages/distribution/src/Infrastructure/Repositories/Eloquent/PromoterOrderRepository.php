<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterOrder;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterOrderRepository extends EloquentRepository implements PromoterOrderRepositoryInterface
{
    protected static string $eloquentModelClass = PromoterOrder::class;
}