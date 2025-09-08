<?php

namespace RedJasmine\Region\Infrastructure\Repositories\Eloquent;

use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class RegionRepository extends Repository implements RegionRepositoryInterface
{

    protected static string $modelClass = Region::class;

}