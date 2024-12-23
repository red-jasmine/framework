<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Refund  find($id)
 */
interface RefundRepositoryInterface extends RepositoryInterface
{

    public function findByNo(string $no) : ?Refund;

}
