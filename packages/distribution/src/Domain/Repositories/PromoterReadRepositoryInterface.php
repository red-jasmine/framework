<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PromoterReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByOwner(Query $owner) : Promoter;
}