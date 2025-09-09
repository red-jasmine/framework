<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\CreatePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\UpdatePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Commands\DeletePromoterTeamCommand;
use RedJasmine\Distribution\Application\PromoterTeam\Services\PromoterTeamApplicationService;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\PromoterTeamPaginateQuery;
use RedJasmine\Distribution\Application\PromoterTeam\Services\Queries\FindPromoterTeamQuery;
use RedJasmine\Distribution\Domain\Data\PromoterTeamData as Data;
use RedJasmine\Distribution\Domain\Models\PromoterTeam as Model;
use RedJasmine\Distribution\UI\Http\Admin\Api\Resources\PromoterTeamResource as Resource;
use RedJasmine\Support\Http\Controllers\Controller;

class PromoterTeamController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PromoterTeamPaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    public function __construct(
        protected PromoterTeamApplicationService $service,
    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->with(['promoters']);
        });
    }

    /**
     * 分销团队列表
     */
    public function index(Request $request)
    {
        $query = PromoterTeamPaginateQuery::from($request);
        $data = $this->service->paginate($query);
        return Resource::collection($data);
    }

    /**
     * 分销团队详情
     */
    public function show(int $id)
    {
        $query = FindPromoterTeamQuery::make($id);
        $team = $this->service->find($query);
        return new Resource($team);
    }

    /**
     * 创建分销团队
     */
    public function store(Request $request)
    {
        $command = CreatePromoterTeamCommand::from($request->all());
        $team = $this->service->create($command);
        return new Resource($team);
    }

    /**
     * 更新分销团队
     */
    public function update(Request $request, int $id)
    {
        $command = UpdatePromoterTeamCommand::from([
            'id' => $id,
            ...$request->all()
        ]);
        $team = $this->service->update($command);
        return new Resource($team);
    }

    /**
     * 删除分销团队
     */
    public function destroy(int $id)
    {
        $command = DeletePromoterTeamCommand::from(['id' => $id]);
        $this->service->delete($command);
        return response()->json(['message' => '删除成功']);
    }
}
