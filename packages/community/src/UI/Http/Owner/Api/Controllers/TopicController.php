<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Controllers;

use RedJasmine\Community\Application\Services\Topic\TopicApplicationService as Service;
use RedJasmine\Community\Application\Services\Topic\Queries\PaginateQuery;
use RedJasmine\Community\Domain\Data\TopicData as Data;
use RedJasmine\Community\Domain\Models\Topic as Model;
use RedJasmine\Community\UI\Http\Owner\Api\Resources\TopicResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class TopicController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected Service $service,
    ) {
        // 设置查询作用域，只显示当前用户的话题
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 检查话题所有权
        if (isset($arguments[0]) && $arguments[0] instanceof Model) {
            return $arguments[0]->owner_id === $this->getOwner()->getID();
        }

        return true;
    }

    /**
     * 发布话题
     */
    public function publish(Model $topic)
    {
        $this->authorize('publish', $topic);

        $this->service->publish($topic);

        return \response()->json([
            'message' => '话题发布成功',
            'data' => new Resource($topic->fresh())
        ]);
    }

    /**
     * 撤回话题为草稿
     */
    public function draft(Model $topic)
    {
        $this->authorize('draft', $topic);

        $this->service->draft($topic);

        return \response()->json([
            'message' => '话题已撤回为草稿',
            'data' => new Resource($topic->fresh())
        ]);
    }
}
