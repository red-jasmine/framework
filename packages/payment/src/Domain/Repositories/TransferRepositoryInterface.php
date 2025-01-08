<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Transfer  find($id)
 */
interface TransferRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : ?Transfer;

}
