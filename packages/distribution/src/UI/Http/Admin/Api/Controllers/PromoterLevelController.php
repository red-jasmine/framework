<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\CreatePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\DeletePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Commands\UpdatePromoterLevelCommand;
use RedJasmine\Distribution\Application\PromoterLevel\Services\PromoterLevelApplicationService;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\FindPromoterLevelQuery;
use RedJasmine\Distribution\Application\PromoterLevel\Services\Queries\PromoterLevelPaginateQuery;
use RedJasmine\Distribution\Domain\Data\PromoterLevelData as Data;
use RedJasmine\Distribution\Domain\Models\PromoterLevel as Model;
use RedJasmine\Distribution\UI\Http\Admin\Api\Resources\PromoterLevelResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\Controller;

class PromoterLevelController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PromoterLevelPaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    public function __construct(
        protected PromoterLevelApplicationService $service,
    ) {
    }

    /**
     * 分销等级列表
     */
    public function index(Request $request)
    {
        $query = PromoterLevelPaginateQuery::from($request);
        $data = $this->service->paginate($query);
        return Resource::collection($data);
    }

    /**
     * 分销等级详情
     */
    public function show(int $id)
    {
        $query = FindPromoterLevelQuery::make($id);
        $level = $this->service->find($query);
        return new Resource($level);
    }

    /**
     * 创建分销等级
     */
    public function store(Request $request)
    {
        $command = CreatePromoterLevelCommand::from($request->all());
        $level = $this->service->create($command);
        return new Resource($level);
    }

    /**
     * 更新分销等级
     */
    public function update(Request $request, int $id)
    {
        $command = UpdatePromoterLevelCommand::from([
            'id' => $id,
            ...$request->all()
        ]);
        $level = $this->service->update($command);
        return new Resource($level);
    }

    /**
     * 删除分销等级
     */
    public function destroy(int $id)
    {
        $command = DeletePromoterLevelCommand::from(['id' => $id]);
        $this->service->delete($command);
        return response()->json(['message' => '删除成功']);
    }
}