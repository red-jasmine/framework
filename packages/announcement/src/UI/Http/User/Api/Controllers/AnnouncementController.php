<?php

namespace RedJasmine\Announcement\UI\Http\User\Api\Controllers;

use RedJasmine\Announcement\Application\Services\AnnouncementApplicationService;
use RedJasmine\Announcement\Application\Services\Queries\AnnouncementListQuery;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Announcement\UI\Http\User\Api\Resources\AnnouncementResource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class AnnouncementController extends Controller
{
    protected static string $resourceClass      = AnnouncementResource::class;
    protected static string $paginateQueryClass = AnnouncementListQuery::class;
    protected static string $modelClass         = Announcement::class;

    use RestQueryControllerActions;

    protected bool $injectionOwner = false;

    public function __construct(
        protected AnnouncementApplicationService $service,
    ) {
        // 设置查询作用域 - 用户只能查看已发布的公告
        $this->service->repository->withQuery(function ($query) {
            $query->where('status', AnnouncementStatus::PUBLISHED);
            // 如果需要按用户范围过滤，可以添加相应的过滤逻辑
            // $query->whereJsonContains('scopes', ['user_id' => $this->getOwner()?->getKey()]);
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 用户端权限验证逻辑 - 用户只能查看公告
        return in_array($ability, ['index', 'show']);
    }
}
