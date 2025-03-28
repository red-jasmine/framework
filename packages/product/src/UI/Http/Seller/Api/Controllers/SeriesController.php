<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\Services\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Application\Series\Services\Queries\SeriesPaginateQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\SeriesResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class SeriesController extends Controller
{

    public function __construct(
        protected ProductSeriesApplicationService $service,


    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(SeriesPaginateQuery::from($request));

        return SeriesResource::collection($result->appends($request->all()));

    }

    public function show($id, Request $request) : SeriesResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return SeriesResource::make($result);
    }


    public function store(Request $request) : SeriesResource
    {

        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductSeriesCreateCommand::from($request);

        $result = $this->service->create($command);

        return SeriesResource::make($result);

    }


    public function update(Request $request, int $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $command = ProductSeriesUpdateCommand::from($request);
        $this->service->update($command);
        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductSeriesDeleteCommand::from($request);

        $this->service->delete($command);

        return static::success();
    }
}
