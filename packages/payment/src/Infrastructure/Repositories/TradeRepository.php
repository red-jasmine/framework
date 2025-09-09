<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class TradeRepository extends Repository implements TradeRepositoryInterface
{

    protected static string $modelClass = Trade::class;

    public function findByNo(string $no) : ?Trade
    {
        return static::$modelClass::where('trade_no', $no)->first();
    }


}

