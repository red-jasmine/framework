<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\Queries\ProductStockLogPaginateQuery;
use RedJasmine\Product\Application\Stock\Services\Queries\ProductStockPaginateQuery;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Application\Stock\Services\StockLogQueryService;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\StockLogResource;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\StockSkuResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Throwable;

class SkuController extends Controller
{
    public function __construct(
        protected StockApplicationService $service,
        protected StockLogQueryService $logQueryService,
    ) {


        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });


        $this->logQueryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(ProductStockPaginateQuery::from($request->all()));


        return StockSkuResource::collection($result->appends($request->all()));

    }


    public function show($id, Request $request) : StockSkuResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return StockSkuResource::make($result);
    }

    /**
     * @param         $id
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws StockException
     * @throws Throwable
     */
    public function action($id, Request $request) : JsonResponse
    {
        $type = $request->input('type', 'add');

        $sku = $this->service->find(FindQuery::make($id));

        $request->offsetSet('sku_id', $sku->id);
        $request->offsetSet('product_id', $sku->product_id);
        $command = StockCommand::from($request);

        switch ($type) {
            case 'add':
                $this->service->add($command);
                break;
            case 'sub':
                $this->service->sub($command);
                break;
            case 'reset':
                $this->service->reset($command);
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
