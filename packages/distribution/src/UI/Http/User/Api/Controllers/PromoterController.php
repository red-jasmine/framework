<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterApplyCommand;
use RedJasmine\Distribution\UI\Http\User\Api\Requests\PromoterApplyRequest;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindPromotersByOwnerQuery;
use RedJasmine\Distribution\Domain\Data\PromoterData as Data;
use RedJasmine\Distribution\Domain\Models\Promoter as Model;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class PromoterController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = FindPromotersByOwnerQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;

    public function __construct(
        protected PromoterApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->with(['parent', 'group', 'team']);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }

    /**
     * 申请成为推广员
     */
    public function apply(PromoterApplyRequest $request)
    {

        $command = PromoterApplyCommand::from([
            'owner' => $request->user(),
            'level' => $request->input('level', 1),
            'parentId' => $request->input('parent_id', 0),

        ]);

        $promoter = $this->service->apply($command);

        return new Resource($promoter);
    }

    /**
     * 重写 index 方法，只显示当前用户的推广员信息
     */
    public function index(Request $request)
    {
        $query = FindPromotersByOwnerQuery::from([
            'owner' => $request->user(),
            'name' => $request->input('name'),
            'page' => $request->input('page', 1),
            'perPage' => $request->input('per_page', 15),
        ]);

        $result = $this->service->findPromotersByOwner($query);

        return Resource::collection($result);
    }
}
