<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockLogQueryService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Application\Stock\UserCases\Queries\ProductStockLogPaginateQuery;
use RedJasmine\Product\Application\Stock\UserCases\Queries\ProductStockPaginateQuery;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\StockLogResource;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\StockSkuResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class SkuController extends Controller
{
    public function __construct(
        protected StockCommandService  $commandService,
        protected StockQueryService    $queryService,
        protected StockLogQueryService $logQueryService,
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

        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return StockSkuResource::make($result);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws StockException
     * @throws \Throwable
     */
    public function action($id, Request $request) : JsonResponse
    {
        $type = $request->input('type', 'add');

        $sku = $this->queryService->findById(FindQuery::make($id));

        $request->offsetSet('sku_id', $sku->id);
        $request->offsetSet('product_id', $sku->product_id);
        $command = StockCommand::from($request);

        switch ($type) {
            case 'add':
                $this->commandService->add($command);
                break;
            case 'sub':
                $this->commandService->sub($command);
                break;
            case 'set':
                $this->commandService->set($command);
                break;
            default:
                abort(405);
                break;
        }

        return static::success();
    }


    public function logs(Request $request) : AnonymousResourceCollection
    {

        $result = $this->logQueryService->paginate(ProductStockLogPaginateQuery::from($request->all()));

        return StockLogResource::collection($result->appends($request->all()));


    }
}
