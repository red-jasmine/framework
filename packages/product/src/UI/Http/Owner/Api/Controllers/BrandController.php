<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\Application\Brand\Services\Queries\BrandPaginateQuery;
use RedJasmine\Product\UI\Http\Owner\Api\Resources\BrandResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class BrandController extends Controller
{
    public function __construct(
        protected BrandApplicationService $service
    ) {

    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(BrandPaginateQuery::from($request));
        return BrandResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, $id) : BrandResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));
        return BrandResource::make($result);
    }
}
