<?php

namespace RedJasmine\Region\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class RegionReadRepository extends QueryBuilderReadRepository implements RegionReadRepositoryInterface
{
    use HasTree;

    public static string $modelClass = Region::class;


}