<?php

namespace RedJasmine\Region\UI\Http\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Region\Application\Services\Country\CountryApplicationService;
use RedJasmine\Region\Application\Services\Country\Queries\CountryPaginateQuery;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\UI\Http\Api\Resources\CountryResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class CountryController extends Controller
{


    use RestQueryControllerActions;


    public function authorize($ability, $arguments = [])
    {
        return true;
    }

    public static string $modelClass         = Country::class;
    public static string $resourceClass      = CountryResource::class;
    public static string $paginateQueryClass = CountryPaginateQuery::class;

    public function __construct(
        protected CountryApplicationService $service
    ) {
    }


}