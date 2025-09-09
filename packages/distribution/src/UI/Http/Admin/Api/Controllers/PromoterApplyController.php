<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\PromoterApply\Services\Commands\PromoterApplyApprovalCommand;
use RedJasmine\Distribution\Application\PromoterApply\Services\PromoterApplyApplicationService;
use RedJasmine\Distribution\Application\PromoterApply\Services\Queries\PromoterApplyPaginateQuery;
use RedJasmine\Distribution\Domain\Data\PromoterApplyData as Data;
use RedJasmine\Distribution\Domain\Models\PromoterApply as Model;
use RedJasmine\Distribution\UI\Http\Admin\Api\Resources\PromoterApplyResource as Resource;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PromoterApplyController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PromoterApplyPaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    public function __construct(
        protected PromoterApplyApplicationService $service,
    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->with(['promoter']);
        });
    }

    /**
     * 分销申请列表
     */
    public function index(Request $request)
    {
        $query = PromoterApplyPaginateQuery::from($request);
        $data = $this->service->paginate($query);
        return Resource::collection($data);
    }

    /**
     * 分销申请详情
     */
    public function show(int $id)
    {
        $query = FindQuery::make($id);
        $apply = $this->service->repository->find($query);
        return new Resource($apply);
    }

    /**
     * 审批分销申请
     */
    public function approval(Request $request, int $id)
    {
        $command = PromoterApplyApprovalCommand::from([
            'id' => $id,
            'status' => $request->input('status'),
            'remark' => $request->input('remark', ''),
        ]);

        $apply = $this->service->approval($command);
        return new Resource($apply);
    }
}
