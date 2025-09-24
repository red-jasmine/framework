<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Controllers;

use RedJasmine\Community\Application\Services\Tag\TopicTagApplicationService as Service;
use RedJasmine\Community\Domain\Data\TopicTagData as Data;
use RedJasmine\Community\Domain\Models\TopicTag as Model;
use RedJasmine\Community\UI\Http\Owner\Api\Resources\TopicTagResource as Resource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class TopicTagController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected Service $service,
    ) {
        // 设置查询作用域，只显示启用的标签
        $this->service->repository->withQuery(function ($query) {
            $query->where('status', 'enable');
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 标签管理权限检查
        return true; // 暂时返回 true，实际项目中需要实现权限检查
    }
}
