<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Application\Services\Queries\ActivityListQuery;
use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\UI\Http\Admin\Api\Requests\ActivityCreateRequest;
use RedJasmine\Promotion\UI\Http\Admin\Api\Requests\ActivityUpdateRequest;
use RedJasmine\Promotion\UI\Http\Admin\Api\Requests\ActivityListRequest;
use RedJasmine\Promotion\UI\Http\Admin\Api\Resources\ActivityResource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 活动管理控制器
 */
class ActivityController extends Controller
{
    use RestControllerActions;

    protected static string $resourceClass = ActivityResource::class;
    protected static string $paginateQueryClass = ActivityListQuery::class;
    protected static string $modelClass = Activity::class;
    protected static string $dataClass = ActivityData::class;

    public function __construct(
        protected ActivityApplicationService $service,
    ) {
        // 设置查询作用域 - 只查询当前管理员有权限的活动
        $this->service->repository->withQuery(function ($query) {
            // 可以根据需要添加权限过滤逻辑
            // $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 这里可以实现具体的权限验证逻辑
        // 例如检查管理员是否有活动管理权限
        return true;
    }

    /**
     * 重写创建方法以使用自定义请求验证
     */
    public function store(ActivityCreateRequest $request): JsonResponse
    {
        $this->authorize('create');

        $data = ActivityData::from($request->validated());
        $model = $this->service->create($data);

        return $this->success(
            new ActivityResource($model),
            '活动创建成功'
        );
    }

    /**
     * 重写更新方法以使用自定义请求验证
     */
    public function update(ActivityUpdateRequest $request, Activity $activity): JsonResponse
    {
        $this->authorize('update', $activity);

        $data = ActivityData::from($request->validated());
        $data->setKey($activity->getKey());
        $model = $this->service->update($data);

        return $this->success(
            new ActivityResource($model),
            '活动更新成功'
        );
    }

    /**
     * 重写列表方法以使用自定义请求验证
     */
    public function index(ActivityListRequest $request): JsonResponse
    {
        $this->authorize('viewAny');

        $query = ActivityListQuery::from($request->validated());
        $result = $this->service->paginate($query);

        return $this->success([
            'data' => ActivityResource::collection($result->items()),
            'meta' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ]
        ]);
    }

    /**
     * 发布活动
     */
    public function publish(Activity $activity): JsonResponse
    {
        $this->authorize('publish', $activity);

        // 检查活动是否可以发布
        if ($activity->status !== ActivityStatusEnum::DRAFT) {
            return $this->error('只有草稿状态的活动才能发布');
        }

        // 更新活动状态为待审核
        $activity->status = ActivityStatusEnum::PENDING;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已提交审核'
        );
    }

    /**
     * 审核通过活动
     */
    public function approve(Activity $activity): JsonResponse
    {
        $this->authorize('approve', $activity);

        if ($activity->status !== ActivityStatusEnum::PENDING) {
            return $this->error('只有待审核状态的活动才能审核通过');
        }

        $activity->status = ActivityStatusEnum::PUBLISHED;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动审核通过'
        );
    }

    /**
     * 审核拒绝活动
     */
    public function reject(Activity $activity): JsonResponse
    {
        $this->authorize('reject', $activity);

        if ($activity->status !== ActivityStatusEnum::PENDING) {
            return $this->error('只有待审核状态的活动才能审核拒绝');
        }

        $activity->status = ActivityStatusEnum::DRAFT;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已拒绝，返回草稿状态'
        );
    }

    /**
     * 启动活动
     */
    public function start(Activity $activity): JsonResponse
    {
        $this->authorize('start', $activity);

        if (!in_array($activity->status, [ActivityStatusEnum::PUBLISHED, ActivityStatusEnum::WARMING])) {
            return $this->error('只有已发布或预热中的活动才能启动');
        }

        $activity->status = ActivityStatusEnum::RUNNING;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已启动'
        );
    }

    /**
     * 暂停活动
     */
    public function pause(Activity $activity): JsonResponse
    {
        $this->authorize('pause', $activity);

        if ($activity->status !== ActivityStatusEnum::RUNNING) {
            return $this->error('只有进行中的活动才能暂停');
        }

        $activity->status = ActivityStatusEnum::PAUSED;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已暂停'
        );
    }

    /**
     * 恢复活动
     */
    public function resume(Activity $activity): JsonResponse
    {
        $this->authorize('resume', $activity);

        if ($activity->status !== ActivityStatusEnum::PAUSED) {
            return $this->error('只有暂停的活动才能恢复');
        }

        $activity->status = ActivityStatusEnum::RUNNING;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已恢复'
        );
    }

    /**
     * 结束活动
     */
    public function end(Activity $activity): JsonResponse
    {
        $this->authorize('end', $activity);

        if (!in_array($activity->status, [ActivityStatusEnum::RUNNING, ActivityStatusEnum::PAUSED])) {
            return $this->error('只有进行中或暂停的活动才能结束');
        }

        $activity->status = ActivityStatusEnum::ENDED;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已结束'
        );
    }

    /**
     * 取消活动
     */
    public function cancel(Activity $activity): JsonResponse
    {
        $this->authorize('cancel', $activity);

        if (in_array($activity->status, [ActivityStatusEnum::ENDED, ActivityStatusEnum::CANCELLED])) {
            return $this->error('已结束或已取消的活动不能再次取消');
        }

        $activity->status = ActivityStatusEnum::CANCELLED;
        $this->service->repository->update($activity);

        return $this->success(
            new ActivityResource($activity),
            '活动已取消'
        );
    }

    /**
     * 复制活动
     */
    public function copy(Activity $activity): JsonResponse
    {
        $this->authorize('create');

        // 创建活动副本
        $newActivityData = ActivityData::from([
            'title' => $activity->title . ' (副本)',
            'description' => $activity->description,
            'type' => $activity->type,
            'client_type' => $activity->client_type,
            'client_id' => $activity->client_id,
            'product_requirements' => $activity->product_requirements,
            'shop_requirements' => $activity->shop_requirements,
            'user_requirements' => $activity->user_requirements,
            'rules' => $activity->rules,
            'overlay_rules' => $activity->overlay_rules,
            'is_show' => false, // 副本默认不显示
        ]);

        $newActivity = $this->service->create($newActivityData);

        return $this->success(
            new ActivityResource($newActivity),
            '活动复制成功'
        );
    }

    /**
     * 获取活动统计信息
     */
    public function statistics(Activity $activity): JsonResponse
    {
        $this->authorize('view', $activity);

        $statistics = [
            'total_products' => $activity->total_products,
            'total_orders' => $activity->total_orders,
            'total_sales' => $activity->total_sales,
            'total_participants' => $activity->total_participants,
            'participation_rate' => $activity->total_participants > 0 ? 
                round(($activity->total_orders / $activity->total_participants) * 100, 2) : 0,
            'average_order_value' => $activity->total_orders > 0 ? 
                round($activity->total_sales / $activity->total_orders, 2) : 0,
        ];

        return $this->success($statistics);
    }
}
