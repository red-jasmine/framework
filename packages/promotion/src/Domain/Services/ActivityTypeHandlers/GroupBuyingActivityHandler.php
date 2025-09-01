<?php

namespace RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers;

use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\ActivityOrder;
use RedJasmine\Promotion\Domain\Models\ActivityProduct;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;
use RedJasmine\Promotion\Domain\Services\AbstractActivityTypeHandler;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 拼团活动处理器
 */
class GroupBuyingActivityHandler extends AbstractActivityTypeHandler
{
    public static string $hookNamePrefix = 'promotion.group_buying';
    
    public function getActivityType(): string
    {
        return ActivityTypeEnum::GROUP_BUYING->value;
    }
    
    public function getExtensionFields(): array
    {
        return [
            'min_group_size' => 'integer', // 最小成团人数
            'max_group_size' => 'integer', // 最大成团人数
            'group_timeout' => 'integer', // 成团超时时间（小时）
            'leader_discount' => 'decimal:2', // 团长额外折扣
            'auto_refund' => 'boolean', // 未成团自动退款
        ];
    }
    
    public function getDefaultRules(): array
    {
        return array_merge(parent::getDefaultRules(), [
            'user_participation_limit' => null, // 拼团可以多次参与
            'min_group_size' => 2,
            'group_timeout' => 24, // 24小时成团时间
            'auto_refund' => true,
        ]);
    }
    
    protected function validateSpecificData(ActivityData $data): void
    {
        $rules = $data->rules;
        
        if (!$rules) {
            throw new \InvalidArgumentException('拼团活动必须设置活动规则');
        }
        
        // 验证最小成团人数
        $minGroupSize = $rules['min_group_size'] ?? 2;
        if ($minGroupSize < 2) {
            throw new \InvalidArgumentException('拼团活动最小成团人数不能少于2人');
        }
        
        // 验证最大成团人数
        $maxGroupSize = $rules['max_group_size'] ?? null;
        if ($maxGroupSize && $maxGroupSize < $minGroupSize) {
            throw new \InvalidArgumentException('最大成团人数不能小于最小成团人数');
        }
    }
    
    protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void
    {
        $groupId = $participationData['group_id'] ?? null;
        $isLeader = $participationData['is_leader'] ?? false;
        
        if ($isLeader) {
            // 开团逻辑验证
            $this->validateGroupCreation($activity, $user, $participationData);
        } else {
            // 参团逻辑验证
            if (!$groupId) {
                throw new \InvalidArgumentException('参团必须指定团ID');
            }
            $this->validateGroupJoining($activity, $user, $groupId, $participationData);
        }
    }
    
    protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float
    {
        $groupPrice = $activityProduct->activity_price;
        $isLeader = $context['is_leader'] ?? false;
        
        // 团长享受额外折扣
        if ($isLeader) {
            $leaderDiscount = $activity->rules['leader_discount'] ?? 0;
            $groupPrice = $groupPrice * (1 - $leaderDiscount / 100);
        }
        
        return $groupPrice * $quantity;
    }
    
    protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void
    {
        $isLeader = $participationData['is_leader'] ?? false;
        $groupId = $participationData['group_id'] ?? null;
        
        if ($isLeader) {
            // 创建新团
            $groupId = $this->createGroup($activity, $activityOrder, $participationData);
        } else {
            // 加入现有团
            $this->joinGroup($activity, $activityOrder, $groupId, $participationData);
        }
        
        $activityOrder->update([
            'group_id' => $groupId,
            'is_leader' => $isLeader,
            'group_status' => $isLeader ? 'pending' : 'joined',
        ]);
        
        // 检查是否成团
        $this->checkGroupCompletion($activity, $groupId);
    }
    
    protected function canStartSpecific(Activity $activity): bool
    {
        // 拼团活动需要有商品
        return $activity->products()->exists();
    }
    
    protected function canEndSpecific(Activity $activity): bool
    {
        // 所有团都已处理完成
        return $activity->participations()
            ->whereIn('group_status', ['pending', 'joined'])
            ->doesntExist();
    }
    
    protected function executeStartLogic(Activity $activity): void
    {
        // 拼团开始时的特定逻辑
        // 比如：初始化团队管理、设置定时任务等
    }
    
    protected function executeEndLogic(Activity $activity): void
    {
        // 拼团结束时的特定逻辑
        // 比如：处理未成团的订单、退款等
        $this->handleUncompletedGroups($activity);
    }
    
    // 拼团特有的辅助方法
    
    private function validateGroupCreation(Activity $activity, UserInterface $user, array $participationData): void
    {
        // 验证是否可以开团
        $productId = $participationData['product_id'] ?? null;
        if (!$productId) {
            throw new \InvalidArgumentException('开团必须指定商品');
        }
        
        $activityProduct = $activity->products()->where('product_id', $productId)->first();
        if (!$activityProduct || $activityProduct->stock <= 0) {
            throw new \RuntimeException('商品库存不足，无法开团');
        }
    }
    
    private function validateGroupJoining(Activity $activity, UserInterface $user, string $groupId, array $participationData): void
    {
        // 验证团是否存在且可加入
        $group = $activity->participations()
            ->where('group_id', $groupId)
            ->where('is_leader', true)
            ->first();
            
        if (!$group) {
            throw new \RuntimeException('团不存在或已失效');
        }
        
        // 检查团是否已满
        $currentMembers = $activity->participations()
            ->where('group_id', $groupId)
            ->count();
            
        $maxGroupSize = $activity->rules['max_group_size'] ?? 10;
        if ($currentMembers >= $maxGroupSize) {
            throw new \RuntimeException('团已满员，无法加入');
        }
        
        // 检查用户是否已在此团中
        $alreadyInGroup = $activity->participations()
            ->where('group_id', $groupId)
            ->where('user_id', $user->getID())
            ->where('user_type', get_class($user))
            ->exists();
            
        if ($alreadyInGroup) {
            throw new \RuntimeException('您已在此团中');
        }
    }
    
    private function createGroup(Activity $activity, ActivityOrder $activityOrder, array $participationData): string
    {
        // 生成团ID
        $groupId = 'group_' . $activity->id . '_' . time() . '_' . rand(1000, 9999);
        
        // 设置成团超时时间
        $groupTimeout = $activity->rules['group_timeout'] ?? 24;
        $timeoutAt = now()->addHours($groupTimeout);
        
        // 可以在这里创建团记录或使用现有的参与记录
        
        return $groupId;
    }
    
    private function joinGroup(Activity $activity, ActivityOrder $activityOrder, string $groupId, array $participationData): void
    {
        // 参团逻辑
        // 更新团成员数量等
    }
    
    private function checkGroupCompletion(Activity $activity, string $groupId): void
    {
        $minGroupSize = $activity->rules['min_group_size'] ?? 2;
        $currentMembers = $activity->participations()
            ->where('group_id', $groupId)
            ->count();
            
        if ($currentMembers >= $minGroupSize) {
            // 成团成功，更新所有团成员状态
            $activity->participations()
                ->where('group_id', $groupId)
                ->update(['group_status' => 'completed']);
        }
    }
    
    private function handleUncompletedGroups(Activity $activity): void
    {
        // 处理未成团的订单
        $uncompletedOrders = $activity->participations()
            ->whereIn('group_status', ['pending', 'joined'])
            ->get();
            
        foreach ($uncompletedOrders as $order) {
            if ($activity->rules['auto_refund'] ?? true) {
                // 自动退款逻辑
                $order->update(['group_status' => 'failed']);
            }
        }
    }
}
