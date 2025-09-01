<?php

namespace RedJasmine\Promotion\Domain\Services;

use RedJasmine\Promotion\Domain\Contracts\ActivityTypeHandlerInterface;
use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\ActivityOrder;
use RedJasmine\Promotion\Domain\Models\ActivityProduct;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 抽象活动类型处理器
 * 
 * 提供活动类型处理器的基础实现
 */
abstract class AbstractActivityTypeHandler extends Service implements ActivityTypeHandlerInterface
{
    /**
     * 验证活动数据的通用逻辑
     */
    public function validateActivityData(ActivityData $data): void
    {
        $this->validateBasicData($data);
        $this->validateSpecificData($data);
    }
    
    /**
     * 验证活动参与条件的通用逻辑
     */
    public function validateParticipation(Activity $activity, UserInterface $user, array $participationData = []): void
    {
        $this->validateActivityStatus($activity);
        $this->validateUserRequirements($activity, $user);
        $this->validateSpecificParticipation($activity, $user, $participationData);
    }
    
    /**
     * 计算价格的通用逻辑
     */
    public function calculatePrice(Activity $activity, ActivityProduct $activityProduct, int $quantity = 1, array $context = []): array
    {
        $originalPrice = $activityProduct->original_price * $quantity;
        $activityPrice = $this->calculateActivityPrice($activity, $activityProduct, $quantity, $context);
        
        return [
            'original_price' => $originalPrice,
            'activity_price' => $activityPrice,
            'discount_amount' => $originalPrice - $activityPrice,
        ];
    }
    
    /**
     * 处理用户参与活动的通用逻辑
     */
    public function handleParticipation(Activity $activity, UserInterface $user, array $participationData): ActivityOrder
    {
        // 验证参与条件
        $this->validateParticipation($activity, $user, $participationData);
        
        // 创建参与记录
        $activityOrder = $this->createParticipationRecord($activity, $user, $participationData);
        
        // 执行特定类型的参与逻辑
        $this->executeParticipationLogic($activity, $activityOrder, $participationData);
        
        return $activityOrder;
    }
    
    /**
     * 检查活动是否可以开始的通用逻辑
     */
    public function canStart(Activity $activity): bool
    {
        return $activity->start_time <= now() 
               && $activity->end_time > now()
               && $this->canStartSpecific($activity);
    }
    
    /**
     * 检查活动是否可以结束的通用逻辑
     */
    public function canEnd(Activity $activity): bool
    {
        return $activity->end_time <= now() || $this->canEndSpecific($activity);
    }
    
    /**
     * 活动开始时的通用处理逻辑
     */
    public function onActivityStart(Activity $activity): void
    {
        $this->hook('starting', $activity, function () use ($activity) {
            $this->executeStartLogic($activity);
        });
    }
    
    /**
     * 活动结束时的通用处理逻辑
     */
    public function onActivityEnd(Activity $activity): void
    {
        $this->hook('ending', $activity, function () use ($activity) {
            $this->executeEndLogic($activity);
        });
    }
    
    /**
     * 获取默认规则
     */
    public function getDefaultRules(): array
    {
        return [
            'user_participation_limit' => null,
            'product_purchase_limit' => null,
            'allow_overlay' => false,
            'new_user_only' => false,
            'member_only' => false,
        ];
    }
    
    // 抽象方法 - 子类必须实现
    
    /**
     * 验证特定活动类型的数据
     */
    abstract protected function validateSpecificData(ActivityData $data): void;
    
    /**
     * 验证特定活动类型的参与条件
     */
    abstract protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void;
    
    /**
     * 计算特定活动类型的价格
     */
    abstract protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float;
    
    /**
     * 执行特定活动类型的参与逻辑
     */
    abstract protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void;
    
    /**
     * 检查特定活动类型是否可以开始
     */
    abstract protected function canStartSpecific(Activity $activity): bool;
    
    /**
     * 检查特定活动类型是否可以结束
     */
    abstract protected function canEndSpecific(Activity $activity): bool;
    
    /**
     * 执行特定活动类型的开始逻辑
     */
    abstract protected function executeStartLogic(Activity $activity): void;
    
    /**
     * 执行特定活动类型的结束逻辑
     */
    abstract protected function executeEndLogic(Activity $activity): void;
    
    // 通用辅助方法
    
    /**
     * 验证基础数据
     */
    protected function validateBasicData(ActivityData $data): void
    {
        if (empty($data->title)) {
            throw new \InvalidArgumentException('活动标题不能为空');
        }
        
        if ($data->startTime >= $data->endTime) {
            throw new \InvalidArgumentException('活动开始时间必须早于结束时间');
        }
        
        if ($data->signUpStartTime && $data->signUpEndTime && $data->signUpStartTime >= $data->signUpEndTime) {
            throw new \InvalidArgumentException('报名开始时间必须早于报名结束时间');
        }
    }
    
    /**
     * 验证活动状态
     */
    protected function validateActivityStatus(Activity $activity): void
    {
        if (!$activity->canParticipate()) {
            throw new \RuntimeException('活动当前不可参与');
        }
    }
    
    /**
     * 验证用户要求
     */
    protected function validateUserRequirements(Activity $activity, UserInterface $user): void
    {
        $userRequirements = $activity->user_requirements;
        
        if (!$userRequirements) {
            return;
        }
        
        // 新用户限制
        if ($userRequirements->newUserOnly && $this->isOldUser($user)) {
            throw new \RuntimeException('此活动仅限新用户参与');
        }
        
        // 会员限制
        if ($userRequirements->memberOnly && !$this->isMember($user)) {
            throw new \RuntimeException('此活动仅限会员参与');
        }
        
        // 用户等级限制
        if ($userRequirements->userLevels && !in_array($this->getUserLevel($user), $userRequirements->userLevels)) {
            throw new \RuntimeException('您的用户等级不符合活动要求');
        }
    }
    
    /**
     * 创建参与记录
     */
    protected function createParticipationRecord(Activity $activity, UserInterface $user, array $participationData): ActivityOrder
    {
        return new ActivityOrder([
            'activity_id' => $activity->id,
            'user_id' => $user->getID(),
            'user_type' => get_class($user),
            'participation_data' => $participationData,
        ]);
    }
    
    // 辅助方法 - 可以根据具体业务需求实现
    
    protected function isOldUser(UserInterface $user): bool
    {
        // 实现判断是否为老用户的逻辑
        return false;
    }
    
    protected function isMember(UserInterface $user): bool
    {
        // 实现判断是否为会员的逻辑
        return true;
    }
    
    protected function getUserLevel(UserInterface $user): ?string
    {
        // 实现获取用户等级的逻辑
        return null;
    }
}
