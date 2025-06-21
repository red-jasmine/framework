<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterApplyCommand;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Domain\Data\PromoterData as Data;
use RedJasmine\Distribution\Domain\Models\Promoter as Model;
use RedJasmine\Distribution\Domain\Facades\PromoterConditionFacade;
use RedJasmine\Distribution\UI\Http\User\Api\Requests\PromoterApplyRequest;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterResource as Resource;

class PromoterController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = FindByOwnerQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;


    public function __construct(
        protected PromoterApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->with(['parent', 'group', 'team']);
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }


    public function info(Request $request) : Resource
    {

        $request->offsetSet('owner', $this->getOwner());
        $query    = FindByOwnerQuery::from($request);
        $promoter = $this->service->findByOwner($query);

        return new Resource($promoter);
    }

    /**
     * 申请成为推广员
     */
    public function apply(PromoterApplyRequest $request) : Resource
    {

        $command = PromoterApplyCommand::from([
            'promoter' => [
                'owner' => $this->getOwner(),
            ]
        ]);


        $promoter = $this->service->apply($command);
        return new Resource($promoter);
    }


}
