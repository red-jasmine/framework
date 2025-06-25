<?php

namespace RedJasmine\Invitation\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Application\Services\Queries\FindQuery;
use RedJasmine\Invitation\Application\Services\Queries\PaginateQuery;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData as Data;
use RedJasmine\Invitation\Domain\Data\UseInvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode as Model;
use RedJasmine\Invitation\UI\Http\User\Api\Resources\InvitationCodeResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 用户端邀请码控制器
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
     * 使用邀请码
     */
    public function use(Request $request): JsonResponse
    {
        $this->validate($request, [
            'code' => 'required|string',
            'target_type' => 'nullable|string',
            'extend_data' => 'nullable|array',
        ]);

        $command = UseInvitationCodeData::from([
            'code' => $request->input('code'),
            'invitee' => $this->getOwner(),
            'target_type' => $request->input('target_type'),
            'extend_data' => $request->input('extend_data', []),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $result = $this->service->use($command);

        return $this->success([
            'message' => '邀请码使用成功',
            'data' => $result,
        ]);
    }

    /**
     * 生成邀请链接
     */
    public function generateUrl(Request $request): JsonResponse
    {
        $this->validate($request, [
            'code' => 'required|string',
            'target_url' => 'required|string|url',
            'target_type' => 'nullable|string',
        ]);

        $url = $this->service->generateInvitationUrl(
            $request->input('code'),
            $request->input('target_url'),
            $request->input('target_type')
        );

        return $this->success([
            'invitation_url' => $url,
        ]);
    }

    /**
     * 获取用户邀请统计
     */
    public function statistics(): JsonResponse
    {
        $owner = $this->getOwner();
        if (!$owner) {
            return $this->error('用户未登录', 401);
        }

        $statistics = $this->service->getUserInvitationStatistics(
            $owner->getID(),
            $owner->getType()
        );

        return $this->success($statistics);
    }
} 