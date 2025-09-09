<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Method  find($id)
 */
interface MethodRepositoryInterface extends RepositoryInterface
{


    public function findByCode(string $code) : ?Method;

}

