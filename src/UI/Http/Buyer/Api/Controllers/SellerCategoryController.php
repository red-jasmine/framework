<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Buyer\Api\Resources\SellerCategoryResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class SellerCategoryController extends Controller
{
    public function __construct(
        protected ProductSellerCategoryQueryService $queryService,

    )
    {

    }

    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductSellerCategoryTreeQuery::from($request));

        return SellerCategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {


        $result = $this->queryService->paginate(ProductSellerCategoryPaginateQuery::from($request));

        return SellerCategoryResource::collection($result->appends($request->query()));
    }


    public function show($id, Request $request) : SellerCategoryResource
    {

        $result = $this->queryService->find($id, FindQuery::from($request));

        return SellerCategoryResource::make($result);
    }

}
