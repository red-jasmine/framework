<?php

namespace RedJasmine\Region\Application\Services\Region;

use RedJasmine\Region\Application\Services\Region\Queries\RegionChildrenQuery;
use RedJasmine\Region\Application\Services\Region\Queries\RegionChildrenQueryHandler;
use RedJasmine\Region\Application\Services\Region\Queries\RegionTreeQuery;
use RedJasmine\Region\Application\Services\Region\Queries\RegionTreeQueryHandler;
use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Region\Domain\Transformers\RegionTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 行政区划应用服务
 *
 * @method array tree(RegionTreeQuery $query)
 * @method array children(RegionChildrenQuery $query)
 */
class RegionApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'region.region.application';

    protected static string $modelClass = Region::class;

    public function __construct(
        public RegionRepositoryInterface $repository,
        public RegionTransformer $transformer,
    ) {
    }

    protected static $macros = [
        'tree'     => RegionTreeQueryHandler::class,
        'children' => RegionChildrenQueryHandler::class
    ];
}
