<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Platform;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Platform  find($id)
 */
interface PlatformRepositoryInterface extends RepositoryInterface
{


    public function findByCode(string $code) : ?Platform;

}
