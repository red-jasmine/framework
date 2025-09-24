<?php

namespace RedJasmine\Product\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Application\Series\Services\Queries\FindProductSeriesQuery;
use RedJasmine\Product\UI\Http\User\Api\Resources\SeriesResource;

class SeriesController extends Controller
{

    public function __construct(
        protected ProductSeriesApplicationService $service,
    ) {
    }


    public function show($id, Request $request) : SeriesResource
    {
        $query = FindProductSeriesQuery::from($request);
        $query->setKey($id);
        $result = $this->service->findProductSeries($query);
        return new SeriesResource($result);

    }


}