<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class TradeRepository extends EloquentRepository implements TradeRepositoryInterface
{

    protected static string $eloquentModelClass = Trade::class;


}
