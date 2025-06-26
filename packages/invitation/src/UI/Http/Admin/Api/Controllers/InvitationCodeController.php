<?php

namespace RedJasmine\Invitation\UI\Http\Admin\Api\Controllers;

use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
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
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;

    public function __construct(
        protected InvitationCodeApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            // 管理员可以查看所有邀请码
            // $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {

        // 这里应该实现权限验证逻辑
        // 暂时返回true，实际项目中需要实现
        return true;
    }
} 