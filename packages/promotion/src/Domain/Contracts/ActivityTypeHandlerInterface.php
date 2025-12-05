<?php

namespace RedJasmine\Promotion\Domain\Contracts;

use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\ActivityOrder;
use RedJasmine\Promotion\Domain\Models\ActivityProduct;
use RedJasmine\Support\Domain\Contracts\UserInterface;

/**
 * 活动类型处理器接口
 * 
 * 定义不同活动类型的通用处理方法
 */
interface ActivityTypeHandlerInterface
{
    /**
     * 获取活动类型
     */
    public function getActivityType(): string;
    
    /**
     * 验证活动数据
     * 
     * @param ActivityData $data
     * @return void
     * @throws \Exception
     */
    public function validateActivityData(ActivityData $data): void;
    
    /**
     * 验证活动参与条件
     * 
     * @param Activity $activity
     * @param UserInterface $user
     * @param array $participationData
     * @return void
     * @throws \Exception
     */
    public function validateParticipation(Activity $activity, UserInterface $user, array $participationData = []): void;
    
    /**
     * 计算活动价格
     * 
     * @param Activity $activity
     * @param ActivityProduct $activityProduct
     * @param int $quantity
     * @param array $context
     * @return array ['original_price' => float, 'activity_price' => float, 'discount_amount' => float]
     */
    public function calculatePrice(Activity $activity, ActivityProduct $activityProduct, int $quantity = 1, array $context = []): array;
    
    /**
     * 处理用户参与活动
     * 
     * @param Activity $activity
     * @param UserInterface $user
     * @param array $participationData
     * @return ActivityOrder
     */
    public function handleParticipation(Activity $activity, UserInterface $user, array $participationData): ActivityOrder;
    
    /**
     * 检查活动是否可以开始
     * 
     * @param Activity $activity
     * @return bool
     */
    public function canStart(Activity $activity): bool;
    
    /**
     * 检查活动是否可以结束
     * 
     * @param Activity $activity
     * @return bool
     */
    public function canEnd(Activity $activity): bool;
    
    /**
     * 活动开始时的处理逻辑
     * 
     * @param Activity $activity
     * @return void
     */
    public function onActivityStart(Activity $activity): void;
    
    /**
     * 活动结束时的处理逻辑
     * 
     * @param Activity $activity
     * @return void
     */
    public function onActivityEnd(Activity $activity): void;
    
    /**
     * 获取活动的扩展配置字段
     * 
     * @return array
     */
    public function getExtensionFields(): array;
    
    /**
     * 获取活动的默认规则
     * 
     * @return array
     */
    public function getDefaultRules(): array;
}
