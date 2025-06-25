<?php

namespace RedJasmine\Invitation\UI\Http\Shop\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Application\Services\Queries\FindQuery;
use RedJasmine\Invitation\Application\Services\Queries\PaginateQuery;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData as Data;
use RedJasmine\Invitation\Domain\Models\InvitationCode as Model;
use RedJasmine\Invitation\UI\Http\Shop\Api\Resources\InvitationCodeResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 商家端邀请码控制器
 */
class InvitationCodeController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected InvitationCodeApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 这里应该实现权限验证逻辑
        // 暂时返回true，实际项目中需要实现
        return true;
    }

    /**
     * 商家邀请统计
     */
    public function statistics(): JsonResponse
    {
        $owner = $this->getOwner();
        if (!$owner) {
            return $this->error('商家未登录', 401);
        }

        $statistics = $this->service->getShopInvitationStatistics(
            $owner->getID(),
            $owner->getType()
        );

        return $this->success($statistics);
    }

    /**
     * 商家邀请码使用记录
     */
    public function usageRecords(Request $request): JsonResponse
    {
        $this->validate($request, [
            'code_id' => 'nullable|integer',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $records = $this->service->getShopUsageRecords(
            $this->getOwner(),
            $request->input('code_id'),
            $request->input('page', 1),
            $request->input('per_page', 15)
        );

        return $this->success($records);
    }

    /**
     * 商家生成邀请码
     */
    public function generate(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
            'target_type' => 'nullable|string',
        ]);

        $command = Data::from([
            'owner' => $this->getOwner(),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'max_uses' => $request->input('max_uses'),
            'expires_at' => $request->input('expires_at'),
            'target_type' => $request->input('target_type'),
        ]);

        $result = $this->service->create($command);

        return $this->success([
            'message' => '邀请码生成成功',
            'data' => $result,
        ]);
    }
} 