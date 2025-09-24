<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Controllers;

use RedJasmine\Community\Application\Services\Category\TopicCategoryApplicationService as Service;
use RedJasmine\Community\Domain\Data\TopicCategoryData as Data;
use RedJasmine\Community\Domain\Models\TopicCategory as Model;
use RedJasmine\Community\UI\Http\Owner\Api\Resources\TopicCategoryResource as Resource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class TopicCategoryController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;
    use HasTreeAction;

    public function __construct(
        protected Service $service,
    ) {
        // 设置查询作用域，只显示启用的分类
        $this->service->repository->withQuery(function ($query) {
            $query->where('is_show', true);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 分类管理权限检查
        return true; // 暂时返回 true，实际项目中需要实现权限检查
    }
}
