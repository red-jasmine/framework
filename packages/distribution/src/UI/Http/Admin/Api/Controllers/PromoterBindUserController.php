<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Application\PromoterBindUser\Commands\PromoterBindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Commands\PromoterUnbindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Queries\PromoterBindUserPaginateQuery;
use RedJasmine\Distribution\Application\PromoterBindUser\Queries\PromoterBindUserFindQuery;
use RedJasmine\Distribution\UI\Http\Admin\Api\Resources\PromoterBindUserResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;

class PromoterBindUserController extends Controller
{
    public function __construct(
        protected PromoterBindUserApplicationService $service
    ) {
    }

    /**
     * 分页查询绑定关系
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = PromoterBindUserPaginateQuery::from($request->all());
        $query->setOwner($request->user());

        $results = $this->service->paginate($query);

        return PromoterBindUserResource::collection($results);
    }

    /**
     * 查看绑定关系详情
     */
    public function show(Request $request, int $id): PromoterBindUserResource
    {
        $query = PromoterBindUserFindQuery::make($id);
        $query->setOwner($request->user());

        $result = $this->service->find($query);

        return PromoterBindUserResource::make($result);
    }

    /**
     * 绑定分销员和用户
     */
    public function store(Request $request): PromoterBindUserResource
    {
        $command = PromoterBindUserCommand::from($request->all());
        $command->setOwner($request->user());

        $result = $this->service->bind($command);

        return PromoterBindUserResource::make($result);
    }

    /**
     * 解绑分销员和用户
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $bindUser = $this->service->find(PromoterBindUserFindQuery::make($id));

        $command = PromoterUnbindUserCommand::from([
            'promoterId'   => $bindUser->promoter_id,
            'user'         => $bindUser->user,
            'unbindReason' => $request->input('unbind_reason', '管理员解绑')
        ]);
        $command->setOwner($request->user());

        $this->service->unbind($command);

        return response()->json(['message' => '解绑成功']);
    }
} 