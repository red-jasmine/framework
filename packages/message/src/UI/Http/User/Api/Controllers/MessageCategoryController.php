<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Message\Application\Services\Commands\MessageCategoryCreateCommand;
use RedJasmine\Message\Application\Services\Commands\MessageCategoryUpdateCommand;
use RedJasmine\Message\Application\Services\MessageCategoryApplicationService;
use RedJasmine\Message\Application\Services\Queries\MessageCategoryListQuery;
use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCategoryCreateRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCategoryListRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCategoryUpdateRequest;
use RedJasmine\Message\UI\Http\User\Api\Resources\MessageCategoryResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 消息分类用户端控制器
 */
class MessageCategoryController
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = MessageCategoryResource::class;
    protected static string $paginateQueryClass = MessageCategoryListQuery::class;
    protected static string $modelClass = MessageCategory::class;
    protected static string $dataClass = MessageCategoryCreateCommand::class;

    public function __construct(
        protected MessageCategoryApplicationService $service,
    ) {
        // 设置查询作用域 - 只能访问自己的分类
        $this->service->readRepository->withQuery(function ($query) {
            $query->where('owner_id', $this->getOwner()->getKey());
        });
    }

    /**
     * 获取分类列表
     */
    public function index(MessageCategoryListRequest $request): JsonResponse
    {
        $query = MessageCategoryListQuery::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
        ]);

        $categories = $this->service->paginate($query);

        return $this->jsonPaginated($categories, static::$resourceClass);
    }

    /**
     * 获取分类树
     */
    public function tree(): JsonResponse
    {
        $tree = $this->service->getTree();

        return $this->jsonSuccess(static::$resourceClass::collection($tree));
    }

    /**
     * 获取启用的分类列表
     */
    public function enabled(): JsonResponse
    {
        $categories = $this->service->getEnabledList();

        return $this->jsonSuccess(static::$resourceClass::collection($categories));
    }

    /**
     * 创建分类
     */
    public function store(MessageCategoryCreateRequest $request): JsonResponse
    {
        $command = MessageCategoryCreateCommand::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
        ]);

        $category = $this->service->create($command);

        return $this->jsonCreated(new static::$resourceClass($category));
    }

    /**
     * 获取分类详情
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        if (!$category) {
            return $this->jsonNotFound('分类不存在');
        }

        return $this->jsonSuccess(new static::$resourceClass($category));
    }

    /**
     * 更新分类
     */
    public function update(MessageCategoryUpdateRequest $request, int $id): JsonResponse
    {
        $command = MessageCategoryUpdateCommand::from([
            ...$request->validated(),
            'id' => $id,
            'operator' => $this->getOwner(),
        ]);

        $category = $this->service->update($command);

        return $this->jsonSuccess(new static::$resourceClass($category));
    }

    /**
     * 删除分类
     */
    public function destroy(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        if (!$category) {
            return $this->jsonNotFound('分类不存在');
        }

        // 检查是否有关联的消息
        if ($category->messages()->exists()) {
            return $this->jsonError('分类下还有消息，无法删除');
        }

        $this->service->delete($id);

        return $this->jsonSuccess(['message' => '删除成功']);
    }

    /**
     * 搜索分类
     */
    public function search(string $keyword): JsonResponse
    {
        $categories = $this->service->searchCategories($keyword);

        return $this->jsonSuccess(static::$resourceClass::collection($categories));
    }

    /**
     * 获取分类路径
     */
    public function path(int $id): JsonResponse
    {
        $path = $this->service->getCategoryPath($id);

        return $this->jsonSuccess($path);
    }

    /**
     * 检查分类名称是否存在
     */
    public function checkName(string $name, ?int $excludeId = null): JsonResponse
    {
        $ownerId = (string) $this->getOwner()->getKey();
        $biz = request('biz', 'default');
        
        $exists = $this->service->existsByName($name, $ownerId, $biz, $excludeId);

        return $this->jsonSuccess([
            'exists' => $exists,
            'available' => !$exists,
        ]);
    }

    /**
     * 获取使用统计
     */
    public function statistics(): JsonResponse
    {
        $statistics = $this->service->getUsageStatistics();

        return $this->jsonSuccess($statistics);
    }

    /**
     * 批量启用分类
     */
    public function batchEnable(array $categoryIds): JsonResponse
    {
        $count = $this->service->batchEnable($categoryIds);

        return $this->jsonSuccess([
            'message' => '批量启用成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 批量禁用分类
     */
    public function batchDisable(array $categoryIds): JsonResponse
    {
        $count = $this->service->batchDisable($categoryIds);

        return $this->jsonSuccess([
            'message' => '批量禁用成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 更新排序
     */
    public function updateSort(array $sortData): JsonResponse
    {
        $count = $this->service->batchUpdateSort($sortData);

        return $this->jsonSuccess([
            'message' => '排序更新成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 用户只能操作自己的分类
        return true;
    }
}
