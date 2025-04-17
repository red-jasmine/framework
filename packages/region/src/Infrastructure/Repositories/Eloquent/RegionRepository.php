<?php

namespace RedJasmine\Region\Infrastructure\Repositories\Eloquent;

use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class RegionRepository extends EloquentRepository implements RegionRepositoryInterface
{

    protected static string $eloquentModelClass = Region::class;

}