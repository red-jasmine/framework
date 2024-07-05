<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Application\Stock\UserCases\Queries\ProductStockPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\StockSkuResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class SkuController extends Controller
{
    public function __construct(
        protected StockCommandService $commandService,
        protected StockQueryService   $queryService,
    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(ProductStockPaginateQuery::from($request->all()));


        return StockSkuResource::collection($result->appends($request->all()));

    }


    public function show($id, Request $request) : StockSkuResource
    {

        $result = $this->queryService->find($id, FindQuery::from($request));

        return StockSkuResource::make($result);
    }


}
