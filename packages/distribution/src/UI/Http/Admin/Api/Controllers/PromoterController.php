<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterUpgradeCommand;
use RedJasmine\Distribution\Application\Promoter\Services\Commands\PromoterDowngradeCommand;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindPromoterByIdQuery;
use RedJasmine\Distribution\Domain\Data\PromoterData as Data;
use RedJasmine\Distribution\Domain\Models\Promoter as Model;
use RedJasmine\Distribution\UI\Http\Admin\Api\Resources\PromoterResource as Resource;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PromoterController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    public function __construct(
        protected PromoterApplicationService $service,
    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->with(['parent', 'group', 'team', 'level']);
        });
    }

    /**
     * 分销员列表
     */
    public function index(Request $request)
    {
        $query = PaginateQuery::from($request);
        $data = $this->service->repository->paginate($query);
        return Resource::collection($data);
    }

    /**
     * 分销员详情
     */
    public function show(int $id)
    {
        $query = FindPromoterByIdQuery::make($id);
        $promoter = $this->service->findPromoterById($query);
        return new Resource($promoter);
    }

    /**
     * 升级分销员
     */
    public function upgrade(Request $request, int $id)
    {
        $command = PromoterUpgradeCommand::from([
            'id' => $id,
            'level' => $request->input('level'),
        ]);

        $promoter = $this->service->upgrade($command);
        return new Resource($promoter);
    }

    /**
     * 降级分销员
     */
    public function downgrade(Request $request, int $id)
    {
        $command = PromoterDowngradeCommand::from([
            'id' => $id,
            'level' => $request->input('level'),
        ]);

        $promoter = $this->service->downgrade($command);
        return new Resource($promoter);
    }
}
