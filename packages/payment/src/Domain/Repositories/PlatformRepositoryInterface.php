<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\PaymentPlatform;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method PaymentPlatform  find($id)
 */
interface PlatformRepositoryInterface extends RepositoryInterface
{


    public function findByCode(string $code) : ?PaymentPlatform;

}
