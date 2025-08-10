<?php

namespace RedJasmine\Announcement\UI\Http\User\Api\Controllers;

use RedJasmine\Announcement\Application\Services\CategoryApplicationService;
use RedJasmine\Announcement\Application\Services\Queries\CategoryListQuery;
use RedJasmine\Announcement\Application\Services\Queries\CategoryTreeQuery;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\UI\Http\User\Api\Resources\CategoryResource;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class CategoryController extends Controller
{
    protected static string $resourceClass      = CategoryResource::class;
    protected static string $paginateQueryClass = CategoryListQuery::class;
    protected static string $treeQueryClass     = CategoryTreeQuery::class;
    protected static string $modelClass         = AnnouncementCategory::class;

    use RestQueryControllerActions;

    use HasTreeAction;
    protected bool $injectionOwner = false;

    public function __construct(
        protected CategoryApplicationService $service,
    ) {
        // 设置查询作用域 - 用户只能查看显示的分类
        $this->service->readRepository->withQuery(function ($query) {
            $query->where('is_show', true);
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 用户端权限验证逻辑 - 用户只能查看分类
        return in_array($ability, ['index', 'show']);
    }


}
