<?php

namespace RedJasmine\Announcement\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Announcement\Application\Services\CategoryApplicationService;
use RedJasmine\Announcement\Application\Services\Queries\CategoryListQuery;
use RedJasmine\Announcement\Domain\Data\CategoryData;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\UI\Http\Admin\Api\Resources\CategoryResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class CategoryController extends Controller
{
    protected static string $resourceClass      = CategoryResource::class;
    protected static string $paginateQueryClass = CategoryListQuery::class;
    protected static string $modelClass         = AnnouncementCategory::class;
    protected static string $dataClass          = CategoryData::class;

    use RestControllerActions;

    use HasTreeAction;

    public function __construct(
        protected CategoryApplicationService $service,
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



}
