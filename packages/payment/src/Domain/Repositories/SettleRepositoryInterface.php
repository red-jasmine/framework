<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Settle  find($id)
 */
interface SettleRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : ?Settle;
}
