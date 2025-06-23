<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterRegisterCommand;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterUpgradeCommand;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Application\PromoterApply\Services\PromoterApplyApplicationService;
use RedJasmine\Distribution\Application\PromoterApply\Services\Queries\PromoterApplyPaginateQuery;
use RedJasmine\Distribution\Domain\Data\PromoterData as Data;
use RedJasmine\Distribution\Domain\Models\Promoter as Model;
use RedJasmine\Distribution\UI\Http\User\Api\Requests\PromoterRegisterRequest;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterResource as Resource;

class PromoterController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = FindByOwnerQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;


    public function __construct(
        protected PromoterApplicationService $service,
        protected PromoterApplyApplicationService $applyApplication,
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
    public function register(PromoterRegisterRequest $request) : Resource
    {
        $command  = PromoterRegisterCommand::from([
            'promoter' => [
                'owner' => $this->getOwner(),
            ]
        ]);
        $promoter = $this->service->register($command);
        return new Resource($promoter);
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



    public function test()  
    {


        
    }

}
