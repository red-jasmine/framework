<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Notify  find($id)
 */
interface NotifyRepositoryInterface extends RepositoryInterface
{

    public function findByNo(string $no) : ?Notify;

}
