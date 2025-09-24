<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Controllers;

use RedJasmine\Announcement\Application\Services\CategoryApplicationService;
use RedJasmine\Announcement\Application\Services\Queries\CategoryListQuery;
use RedJasmine\Announcement\Application\Services\Queries\CategoryTreeQuery;
use RedJasmine\Announcement\Domain\Data\CategoryData;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\UI\Http\Owner\Api\Resources\CategoryResource;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class CategoryController extends Controller
{
    protected static string $resourceClass      = CategoryResource::class;
    protected static string $paginateQueryClass = CategoryListQuery::class;
    protected static string $treeQueryClass     = CategoryTreeQuery::class;
    protected static string $modelClass        = AnnouncementCategory::class;
    protected static string $dataClass         = CategoryData::class;

    use RestControllerActions;
    use HasTreeAction;

    public function __construct(
        protected CategoryApplicationService $service,
    ) {
        // 设置查询作用域 - Owner 只能查看自己的分类
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
            'show', 'hide', 'move' => true, // 状态操作权限
            default => false,
        };
    }

    /**
     * 显示分类
     */
    public function show(AnnouncementCategory $category)
    {
        $this->authorize('show', $category);

        $result = $this->service->show($category, $this->getOwner());

        return new CategoryResource($result);
    }

    /**
     * 隐藏分类
     */
    public function hide(AnnouncementCategory $category)
    {
        $this->authorize('hide', $category);

        $result = $this->service->hide($category, $this->getOwner());

        return new CategoryResource($result);
    }

    /**
     * 移动分类
     */
    public function move(AnnouncementCategory $category)
    {
        $this->authorize('move', $category);

        $parentId = \request('parent_id');
        $result = $this->service->move($category, $parentId, $this->getOwner());

        return new CategoryResource($result);
    }
}
