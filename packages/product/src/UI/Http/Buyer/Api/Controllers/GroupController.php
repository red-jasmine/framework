<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Buyer\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(
        protected ProductGroupApplicationService $service,

    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }

    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->service->tree(ProductGroupTreeQuery::from($request));

        return GroupResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {


        $result = $this->service->paginate(ProductGroupPaginateQuery::from($request));

        return GroupResource::collection($result->appends($request->query()));
    }


    public function show($id, Request $request) : GroupResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return GroupResource::make($result);
    }

}
