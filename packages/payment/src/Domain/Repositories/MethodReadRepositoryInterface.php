<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface MethodReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findByCode(Query $query) : ?Method;

}
