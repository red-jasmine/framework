<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\ProductGroupQueryService;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Buyer\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(
        protected ProductGroupQueryService $queryService,

    )
    {

        $this->queryService->onlyShow();

    }

    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductGroupTreeQuery::from($request));

        return GroupResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {


        $result = $this->queryService->paginate(ProductGroupPaginateQuery::from($request));

        return GroupResource::collection($result->appends($request->query()));
    }


    public function show($id, Request $request) : GroupResource
    {

        $result = $this->queryService->findById(FindQuery::make($id,$request));

        return GroupResource::make($result);
    }

}
