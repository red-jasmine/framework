<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\BrandResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class BrandController extends Controller
{
    public function __construct(

        protected BrandQueryService $queryService
    )
    {
        $this->queryService->onlyShow();
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PaginateQuery::from($request));
        return BrandResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, $id) : BrandResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));
        return BrandResource::make($result);
    }
}
