<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Message\Application\Services\Commands\MessageTemplateCreateCommand;
use RedJasmine\Message\Application\Services\Commands\MessageTemplateUpdateCommand;
use RedJasmine\Message\Application\Services\MessageTemplateApplicationService;
use RedJasmine\Message\Application\Services\Queries\MessageTemplateListQuery;
use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageTemplateCreateRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageTemplateListRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageTemplateUpdateRequest;
use RedJasmine\Message\UI\Http\User\Api\Resources\MessageTemplateResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 消息模板用户端控制器
 */
class MessageTemplateController
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = MessageTemplateResource::class;
    protected static string $paginateQueryClass = MessageTemplateListQuery::class;
    protected static string $modelClass = MessageTemplate::class;
    protected static string $dataClass = MessageTemplateCreateCommand::class;

    public function __construct(
        protected MessageTemplateApplicationService $service,
    ) {
        // 设置查询作用域 - 可以访问系统模板和自己的模板
        $this->service->repository->withQuery(function ($query) {
            $query->where(function ($q) {
                $q->where('owner_id', $this->getOwner()->getKey())
                  ->orWhere('is_system', true);
            });
        });
    }

    /**
     * 获取模板列表
     */
    public function index(MessageTemplateListRequest $request): JsonResponse
    {
        $query = MessageTemplateListQuery::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
        ]);

        $templates = $this->service->paginate($query);

        return $this->jsonPaginated($templates, static::$resourceClass);
    }

    /**
     * 获取启用的模板列表
     */
    public function enabled(): JsonResponse
    {
        $templates = $this->service->getEnabledList();

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 获取热门模板
     */
    public function popular(int $limit = 10): JsonResponse
    {
        $templates = $this->service->getPopular($limit);

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 根据业务线获取模板
     */
    public function byBiz(string $biz): JsonResponse
    {
        $templates = $this->service->getByBiz($biz);

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 根据分类获取模板
     */
    public function byCategory(int $categoryId): JsonResponse
    {
        $templates = $this->service->getByCategory($categoryId);

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 根据类型获取模板
     */
    public function byType(string $type): JsonResponse
    {
        $templates = $this->service->getByType($type);

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 创建模板
     */
    public function store(MessageTemplateCreateRequest $request): JsonResponse
    {
        $command = MessageTemplateCreateCommand::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
        ]);

        $template = $this->service->create($command);

        return $this->jsonCreated(new static::$resourceClass($template));
    }

    /**
     * 获取模板详情
     */
    public function show(int $id): JsonResponse
    {
        $template = $this->service->find($id);

        if (!$template) {
            return $this->jsonNotFound('模板不存在');
        }

        return $this->jsonSuccess(new static::$resourceClass($template));
    }

    /**
     * 根据编码获取模板
     */
    public function byCode(string $code): JsonResponse
    {
        $template = $this->service->findByCode($code);

        if (!$template) {
            return $this->jsonNotFound('模板不存在');
        }

        return $this->jsonSuccess(new static::$resourceClass($template));
    }

    /**
     * 更新模板
     */
    public function update(MessageTemplateUpdateRequest $request, int $id): JsonResponse
    {
        $template = $this->service->find($id);

        if (!$template) {
            return $this->jsonNotFound('模板不存在');
        }

        // 检查是否是系统模板
        if ($template->is_system) {
            return $this->jsonError('系统模板不能修改');
        }

        // 检查是否是自己的模板
        if ($template->owner_id !== (string) $this->getOwner()->getKey()) {
            return $this->jsonError('没有权限修改此模板');
        }

        $command = MessageTemplateUpdateCommand::from([
            ...$request->validated(),
            'id' => $id,
            'operator' => $this->getOwner(),
        ]);

        $template = $this->service->update($command);

        return $this->jsonSuccess(new static::$resourceClass($template));
    }

    /**
     * 删除模板
     */
    public function destroy(int $id): JsonResponse
    {
        $template = $this->service->find($id);

        if (!$template) {
            return $this->jsonNotFound('模板不存在');
        }

        // 检查是否是系统模板
        if ($template->is_system) {
            return $this->jsonError('系统模板不能删除');
        }

        // 检查是否是自己的模板
        if ($template->owner_id !== (string) $this->getOwner()->getKey()) {
            return $this->jsonError('没有权限删除此模板');
        }

        $this->service->delete($id);

        return $this->jsonSuccess(['message' => '删除成功']);
    }

    /**
     * 复制模板
     */
    public function duplicate(int $id): JsonResponse
    {
        $originalTemplate = $this->service->find($id);

        if (!$originalTemplate) {
            return $this->jsonNotFound('模板不存在');
        }

        $newTemplate = $this->service->duplicateTemplate($id);

        if (!$newTemplate) {
            return $this->jsonError('复制失败');
        }

        return $this->jsonCreated(new static::$resourceClass($newTemplate));
    }

    /**
     * 搜索模板
     */
    public function search(string $keyword): JsonResponse
    {
        $templates = $this->service->searchTemplates($keyword);

        return $this->jsonSuccess(static::$resourceClass::collection($templates));
    }

    /**
     * 预览模板
     */
    public function preview(int $id, array $variables = []): JsonResponse
    {
        $template = $this->service->find($id);

        if (!$template) {
            return $this->jsonNotFound('模板不存在');
        }

        // 这里可以调用模板服务来渲染预览
        $preview = [
            'title' => $template->title,
            'content' => $template->content,
            'rendered_title' => $template->title, // 实际应该进行变量替换
            'rendered_content' => $template->content, // 实际应该进行变量替换
            'variables' => $template->variables ?? [],
            'provided_variables' => $variables,
        ];

        return $this->jsonSuccess($preview);
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
     * 获取模板变量统计
     */
    public function variableStatistics(): JsonResponse
    {
        $statistics = $this->service->getVariableStatistics();

        return $this->jsonSuccess($statistics);
    }

    /**
     * 检查模板名称是否存在
     */
    public function checkName(string $name, ?int $excludeId = null): JsonResponse
    {
        $exists = $this->service->existsByName($name, $excludeId);

        return $this->jsonSuccess([
            'exists' => $exists,
            'available' => !$exists,
        ]);
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 用户可以查看系统模板，但只能操作自己的模板
        return true;
    }
}
