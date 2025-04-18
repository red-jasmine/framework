<?php

namespace RedJasmine\Region\UI\Http\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Region\Application\Services\Region\Queries\RegionChildrenQuery;
use RedJasmine\Region\Application\Services\Region\Queries\RegionTreeQuery;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;

class RegionController extends Controller
{


    public function __construct(
        protected RegionApplicationService $service
    ) {
    }


    public function tree(Request $request)
    {
        $query = RegionTreeQuery::from($request);
        $tree  = $this->service->tree($query);
        return static::success($tree);
    }

    public function children(Request $request)
    {
        $query = RegionChildrenQuery::from($request);
        $tree  = $this->service->children($query);
        return static::success($tree);
    }
}