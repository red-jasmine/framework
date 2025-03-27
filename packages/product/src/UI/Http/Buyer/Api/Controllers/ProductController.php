<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\UI\Http\Buyer\Api\Resources\ProductResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductController extends Controller
{

    public function __construct(
        protected ProductApplicationService $queryService,

    ) {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PaginateQuery::from($request->all()));

        return ProductResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : ProductResource
    {
        $result = $this->queryService->find(FindQuery::make($id, $request));
        return ProductResource::make($result);
    }


}
