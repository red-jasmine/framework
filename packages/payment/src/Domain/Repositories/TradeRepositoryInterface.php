<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Trade  find($id)
 */
interface TradeRepositoryInterface extends RepositoryInterface
{

    public function findByTradeNo(string $tradeNo) : ?Trade;

}
