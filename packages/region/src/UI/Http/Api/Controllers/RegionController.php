<?php

namespace RedJasmine\Region\UI\Http\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use RedJasmine\Region\Application\Services\Region\Queries\RegionChildrenQuery;
use RedJasmine\Region\Application\Services\Region\Queries\RegionTreeQuery;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;

class RegionController extends Controller
{


    public function __construct(
        protected RegionApplicationService $service
    ) {
    }


    public function tree(Request $request) : JsonResponse|JsonResource
    {
        $query = RegionTreeQuery::from($request);
        // 添加缓存
        $key  = 'regions:'.$query->countryCode.'_'.$query->level;
        $tree = Cache::driver('file')->remember($key, (24 * 60 * 60) * 5, function () use ($query) {
            return $this->service->tree($query);
        });
        return static::success($tree);
    }

    public function children(Request $request) : JsonResponse|JsonResource
    {
        $query = RegionChildrenQuery::from($request);
        $tree  = $this->service->children($query);
        return static::success($tree);
    }
}