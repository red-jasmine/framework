<?php

namespace RedJasmine\Promotion\Application\Services;

use Exception;
use RedJasmine\Promotion\Application\Services\Commands\ActivityCreateCommandHandler;
use RedJasmine\Promotion\Application\Services\Commands\ActivityParticipateCommandHandler;
use RedJasmine\Promotion\Application\Services\Commands\ActivityUpdateCommandHandler;
use RedJasmine\Promotion\Application\Services\Queries\ActivityFindQueryHandler;
use RedJasmine\Promotion\Application\Services\Queries\ActivityPaginateQueryHandler;
use RedJasmine\Promotion\Domain\Contracts\ActivityTypeHandlerInterface;
use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Repositories\ActivityRepositoryInterface;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlerFactory;
use RedJasmine\Promotion\Domain\Transformers\ActivityTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 活动应用服务
 */
class ActivityApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'promotion.activity.application';
    protected static string $modelClass     = Activity::class;
    protected static $macros = [
        'create'      => ActivityCreateCommandHandler::class,
        'update'      => ActivityUpdateCommandHandler::class,
        'find'        => ActivityFindQueryHandler::class,
        'paginate'    => ActivityPaginateQueryHandler::class,
        'participate' => ActivityParticipateCommandHandler::class,
    ];

    public function __construct(
        public ActivityRepositoryInterface $repository,
        public ActivityTransformer $transformer
    ) {
    }

    /**
     * 验证活动数据
     *
     * @param  ActivityData  $data
     *
     * @return void
     * @throws Exception
     */
    public function validateActivityData($data) : void
    {
        $handler = $this->getTypeHandler($data->type->value);
        $handler->validateActivityData($data);
    }

    /**
     * 获取活动类型处理器
     *
     * @param  string|Activity  $activityOrType
     *
     * @return ActivityTypeHandlerInterface
     */
    public function getTypeHandler(string|Activity $activityOrType) : ActivityTypeHandlerInterface
    {
        $type = $activityOrType instanceof Activity ? $activityOrType->type->value : $activityOrType;
        return ActivityTypeHandlerFactory::make($type);
    }

    /**
     * 计算活动价格
     *
     * @param  Activity  $activity
     * @param  int  $productId
     * @param  int  $quantity
     * @param  array  $context
     *
     * @return array
     */
    public function calculateActivityPrice(Activity $activity, int $productId, int $quantity = 1, array $context = []) : array
    {
        $handler         = $this->getTypeHandler($activity);
        $activityProduct = $activity->products()->where('product_id', $productId)->first();

        if (!$activityProduct) {
            throw new \RuntimeException('商品未参与此活动');
        }

        return $handler->calculatePrice($activity, $activityProduct, $quantity, $context);
    }

    /**
     * 处理用户参与活动
     *
     * @param  Activity  $activity
     * @param  \RedJasmine\Support\Domain\Contracts\UserInterface  $user
     * @param  array  $participationData
     *
     * @return \RedJasmine\Promotion\Domain\Models\ActivityOrder
     */
    public function handleParticipation(Activity $activity, $user, array $participationData)
    {
        $handler = $this->getTypeHandler($activity);
        return $handler->handleParticipation($activity, $user, $participationData);
    }

    /**
     * 开始活动
     *
     * @param  Activity  $activity
     *
     * @return void
     */
    public function startActivity(Activity $activity) : void
    {
        $handler = $this->getTypeHandler($activity);

        if (!$handler->canStart($activity)) {
            throw new \RuntimeException('活动当前不能开始');
        }

        $activity->update(['status' => \RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum::RUNNING]);
        $handler->onActivityStart($activity);
    }

    /**
     * 结束活动
     *
     * @param  Activity  $activity
     *
     * @return void
     */
    public function endActivity(Activity $activity) : void
    {
        $handler = $this->getTypeHandler($activity);

        if (!$handler->canEnd($activity)) {
            throw new \RuntimeException('活动当前不能结束');
        }

        $activity->update(['status' => \RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum::ENDED]);
        $handler->onActivityEnd($activity);
    }

    /**
     * 获取活动类型的扩展字段配置
     *
     * @param  string  $activityType
     *
     * @return array
     */
    public function getActivityTypeExtensionFields(string $activityType) : array
    {
        $handler = $this->getTypeHandler($activityType);
        return $handler->getExtensionFields();
    }

    /**
     * 获取活动类型的默认规则
     *
     * @param  string  $activityType
     *
     * @return array
     */
    public function getActivityTypeDefaultRules(string $activityType) : array
    {
        $handler = $this->getTypeHandler($activityType);
        return $handler->getDefaultRules();
    }

    /**
     * 获取所有已注册的活动类型
     *
     * @return array
     */
    public function getRegisteredActivityTypes() : array
    {
        return ActivityTypeHandlerFactory::getRegisteredTypes();
    }

    /**
     * 检查活动是否可以参与
     *
     * @param  Activity  $activity
     * @param  \RedJasmine\Support\Domain\Contracts\UserInterface  $user
     * @param  array  $participationData
     *
     * @return bool
     */
    public function canParticipate(Activity $activity, $user, array $participationData = []) : bool
    {
        try {
            $handler = $this->getTypeHandler($activity);
            $handler->validateParticipation($activity, $user, $participationData);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 获取活动参与失败原因
     *
     * @param  Activity  $activity
     * @param  \RedJasmine\Support\Domain\Contracts\UserInterface  $user
     * @param  array  $participationData
     *
     * @return string|null
     */
    public function getParticipationFailureReason(Activity $activity, $user, array $participationData = []) : ?string
    {
        try {
            $handler = $this->getTypeHandler($activity);
            $handler->validateParticipation($activity, $user, $participationData);
            return null;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 批量计算活动价格
     *
     * @param  Activity  $activity
     * @param  array  $items  [['product_id' => int, 'quantity' => int], ...]
     * @param  array  $context
     *
     * @return array
     */
    public function calculateBatchActivityPrice(Activity $activity, array $items, array $context = []) : array
    {
        $handler            = $this->getTypeHandler($activity);
        $results            = [];
        $totalOriginalPrice = 0;
        $totalActivityPrice = 0;

        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity  = $item['quantity'] ?? 1;

            $activityProduct = $activity->products()->where('product_id', $productId)->first();
            if (!$activityProduct) {
                continue;
            }

            $priceInfo = $handler->calculatePrice($activity, $activityProduct, $quantity, $context);
            $results[] = array_merge($priceInfo, [
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);

            $totalOriginalPrice += $priceInfo['original_price'];
            $totalActivityPrice += $priceInfo['activity_price'];
        }

        return [
            'items'                 => $results,
            'total_original_price'  => $totalOriginalPrice,
            'total_activity_price'  => $totalActivityPrice,
            'total_discount_amount' => $totalOriginalPrice - $totalActivityPrice,
        ];
    }
}
