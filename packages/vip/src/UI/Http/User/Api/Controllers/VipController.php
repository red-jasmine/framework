<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Vip\Application\Services\VipQueryService;
use RedJasmine\Vip\UI\Http\User\Api\Resources\VipResource;

class VipController extends Controller
{

    public function __construct(

        public VipQueryService $queryService
    ) {
    }

    public function index(Request $request)
    {
        $result = $this->queryService->paginate(PaginateQuery::from($request));

        return VipResource::collection($result);
    }

    public function show(string $appId, string $type)
    {
        $result = $this->queryService->findVipType(
            $appId,
            $type,
           );

        return   VipResource::make($result);
    }
}
