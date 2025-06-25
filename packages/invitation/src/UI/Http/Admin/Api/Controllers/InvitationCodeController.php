<?php

namespace RedJasmine\Invitation\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Application\Services\Queries\FindQuery;
use RedJasmine\Invitation\Application\Services\Queries\PaginateQuery;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData as Data;
use RedJasmine\Invitation\Domain\Models\InvitationCode as Model;
use RedJasmine\Invitation\UI\Http\Admin\Api\Resources\InvitationCodeResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 邀请码控制器
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
            // 管理员可以查看所有邀请码
            // $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 这里应该实现权限验证逻辑
        // 暂时返回true，实际项目中需要实现
        return true;
    }

    /**
     * 邀请码统计
     */
    public function statistics(): JsonResponse
    {
        $statistics = $this->service->getAdminInvitationStatistics();

        return $this->success($statistics);
    }

    /**
     * 邀请码分析
     */
    public function analytics(Request $request): JsonResponse
    {
        $this->validate($request, [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'group_by' => 'nullable|string|in:day,week,month',
        ]);

        $analytics = $this->service->getAdminInvitationAnalytics(
            $request->input('start_date'),
            $request->input('end_date'),
            $request->input('group_by', 'day')
        );

        return $this->success($analytics);
    }

    /**
     * 批量删除邀请码
     */
    public function batchDelete(Request $request): JsonResponse
    {
        $this->validate($request, [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:invitation_codes,id',
        ]);

        $deletedCount = $this->service->batchDelete($request->input('ids'));

        return $this->success([
            'message' => "成功删除 {$deletedCount} 个邀请码",
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * 批量更新邀请码
     */
    public function batchUpdate(Request $request): JsonResponse
    {
        $this->validate($request, [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:invitation_codes,id',
            'status' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $updatedCount = $this->service->batchUpdate(
            $request->input('ids'),
            $request->only(['status', 'expires_at'])
        );

        return $this->success([
            'message' => "成功更新 {$updatedCount} 个邀请码",
            'updated_count' => $updatedCount,
        ]);
    }
} 