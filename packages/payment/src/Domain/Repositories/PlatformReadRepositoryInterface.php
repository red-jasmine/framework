<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\PaymentPlatform;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PlatformReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findByCode(Query $query) : ?PaymentPlatform;

}
