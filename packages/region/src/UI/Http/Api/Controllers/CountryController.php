<?php

namespace RedJasmine\Region\UI\Http\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Region\Application\Services\Country\CountryApplicationService;
use RedJasmine\Region\Application\Services\Country\Queries\CountryPaginateQuery;
use RedJasmine\Region\UI\Http\Api\Resources\CountryResource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class CountryController extends Controller
{


    public function __construct(
        protected CountryApplicationService $service
    ) {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $query = CountryPaginateQuery::from($request);

        $result = $this->service->readRepository->paginate($query);


        return CountryResource::collection($result);
    }


}