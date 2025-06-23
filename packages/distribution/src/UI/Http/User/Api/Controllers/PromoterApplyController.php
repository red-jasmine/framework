<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterUpgradeCommand;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Application\PromoterApply\Services\PromoterApplyApplicationService;
use RedJasmine\Distribution\Application\PromoterApply\Services\Queries\PromoterApplyPaginateQuery;
use RedJasmine\Distribution\Domain\Data\PromoterData as Data;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterApply as Model;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterApplyResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class PromoterApplyController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PromoterApplyPaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestQueryControllerActions;

    use HasPromoter;


    public function __construct(
        protected PromoterApplicationService $promoterApplicationService,
        protected PromoterApplyApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->with(['promoterLevel'])->onlyPromoter($this->getPromoter());
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }


    public function upgrade(Request $request) : Resource
    {

        $request->offsetSet('owner', $this->getOwner());
        $query    = FindByOwnerQuery::from($request);
        $promoter = $this->service->findByOwner($query);


        $command = PromoterUpgradeCommand::from([
            'id'    => $promoter->id,
            'level' => $promoter->level + 1,
        ]);

        $promoter = $this->service->upgrade($command);
        return new Resource($promoter);
    }


}
