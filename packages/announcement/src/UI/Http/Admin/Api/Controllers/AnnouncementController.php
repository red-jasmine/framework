<?php

namespace RedJasmine\Announcement\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Announcement\Application\Services\AnnouncementApplicationService;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementPublishCommand;
use RedJasmine\Announcement\Application\Services\Commands\AnnouncementRevokeCommand;
use RedJasmine\Announcement\Application\Services\Queries\AnnouncementListQuery;
use RedJasmine\Announcement\Domain\Data\AnnouncementData;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\UI\Http\Admin\Api\Resources\AnnouncementResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class AnnouncementController extends Controller
{
    protected static string $resourceClass      = AnnouncementResource::class;
    protected static string $paginateQueryClass = AnnouncementListQuery::class;
    protected static string $modelClass         = Announcement::class;
    protected static string $dataClass          = AnnouncementData::class;

    use RestControllerActions;


    public function __construct(
        protected AnnouncementApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 权限验证逻辑
        return true;
    }

    /**
     * 发布公告
     */
    public function publish(int $id) : JsonResponse
    {
        $command = new AnnouncementPublishCommand();
        $command->setKey($id);
        $this->service->publish($command);

        return static::success([], '发布成功');

    }

    /**
     * 撤销公告
     */
    public function revoke(int $id) : JsonResponse
    {
        $command = new AnnouncementRevokeCommand();
        $command->setKey($id);
        $this->service->revoke($command);
        return static::success([], '撤销成功');
    }

}
