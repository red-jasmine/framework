<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Controllers;

use RedJasmine\Announcement\Application\Services\AnnouncementApplicationService;
use RedJasmine\Announcement\Application\Services\Queries\AnnouncementListQuery;
use RedJasmine\Announcement\Domain\Data\AnnouncementData;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\UI\Http\Owner\Api\Resources\AnnouncementResource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class AnnouncementController extends Controller
{
    protected static string $resourceClass      = AnnouncementResource::class;
    protected static string $paginateQueryClass = AnnouncementListQuery::class;
    protected static string $modelClass         = Announcement::class;
    protected static string $dataClass         = AnnouncementData::class;

    use RestControllerActions;

    public function __construct(
        protected AnnouncementApplicationService $service,
    ) {
        // 设置查询作用域 - Owner 只能查看自己的公告
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // Owner 端权限验证逻辑
        return match ($ability) {
            'index', 'show' => true, // 查看权限
            'store', 'update', 'destroy' => true, // 管理权限
            'publish', 'revoke', 'submitApproval', 'approve', 'reject' => true, // 状态操作权限
            default => false,
        };
    }

    /**
     * 发布公告
     */
    public function publish(Announcement $announcement)
    {
        $this->authorize('publish', $announcement);

        $result = $this->service->publish($announcement, $this->getOwner());

        return new AnnouncementResource($result);
    }

    /**
     * 撤销公告
     */
    public function revoke(Announcement $announcement)
    {
        $this->authorize('revoke', $announcement);

        $result = $this->service->revoke($announcement, $this->getOwner());

        return new AnnouncementResource($result);
    }

    /**
     * 提交审批
     */
    public function submitApproval(Announcement $announcement)
    {
        $this->authorize('submitApproval', $announcement);

        $result = $this->service->submitApproval($announcement, $this->getOwner());

        return new AnnouncementResource($result);
    }

    /**
     * 审批通过
     */
    public function approve(Announcement $announcement)
    {
        $this->authorize('approve', $announcement);

        $result = $this->service->approve($announcement, $this->getOwner());

        return new AnnouncementResource($result);
    }

    /**
     * 审批拒绝
     */
    public function reject(Announcement $announcement)
    {
        $this->authorize('reject', $announcement);

        $result = $this->service->reject($announcement, $this->getOwner());

        return new AnnouncementResource($result);
    }
}
