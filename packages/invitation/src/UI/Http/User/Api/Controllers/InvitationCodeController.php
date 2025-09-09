<?php

namespace RedJasmine\Invitation\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
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
    )
    {
        $this->service->repository->withQuery(function ($query) {
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


} 