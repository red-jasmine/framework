<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductCategoryApplicationService;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Owner\Api\Resources\CategoryResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CategoryController extends Controller
{

    public function __construct(
        protected ProductCategoryApplicationService $service,

    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->service->tree(ProductCategoryTreeQuery::from($request));
        return CategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(ProductCategoryPaginateQuery::from($request));

        return CategoryResource::collection($result);

    }

    public function show(Request $request, $id) : CategoryResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return CategoryResource::make($result);

    }

}
