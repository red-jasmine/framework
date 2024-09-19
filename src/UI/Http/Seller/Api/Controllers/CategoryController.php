<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\CategoryResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CategoryController extends Controller
{

    public function __construct(
        protected ProductCategoryQueryService $queryService,

    )
    {
        $this->queryService->onlyShow();
    }

    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductCategoryTreeQuery::from($request));
        return CategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(ProductCategoryPaginateQuery::from($request));

        return CategoryResource::collection($result);

    }

    public function show(Request $request, $id) : CategoryResource
    {
        $result = $this->queryService->find(FindQuery::fromRequestRoute($request,$id));;

        return CategoryResource::make($result);

    }

}
