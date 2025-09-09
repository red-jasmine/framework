<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use RedJasmine\Distribution\Application\PromoterBindUser\Services\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\Queries\PromoterBindUserPaginateQuery;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser as Model;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterBindUserResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;


class PromoterBindUserController extends Controller
{
    use HasPromoter;
    use RestQueryControllerActions;

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PromoterBindUserPaginateQuery::class;
    protected static string $modelClass         = Model::class;


    public function __construct(
        protected PromoterBindUserApplicationService $service
    ) {

        $this->service->repository->withQuery(function ($query) {
            $query->onlyPromoter($this->getPromoter());
        });
    }

    public function authorize($ability, $arguments = [])
    {
        return true;
    }


}
