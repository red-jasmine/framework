<?php

namespace RedJasmine\ResourceUsage\Domain\Repositories;

use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ResourceUsageReadRepositoryInterface extends ReadRepositoryInterface
{

    public function queryResourceUsage(OwnerInterface $owner,string $name);


}