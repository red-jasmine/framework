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
 * 秒杀活动处理器
 */
class FlashSaleActivityHandler extends AbstractActivityTypeHandler
{
    public static string $hookNamePrefix = 'promotion.flash_sale';
    
    public function getActivityType(): string
    {
        return ActivityTypeEnum::FLASH_SALE->value;
    }
    
    public function getExtensionFields(): array
    {
        return [
            'max_participants' => 'integer', // 最大参与人数
            'current_participants' => 'integer', // 当前参与人数
            'flash_price' => 'decimal:2', // 秒杀价格
            'limit_per_user' => 'integer', // 每人限购数量
        ];
    }
    
    public function getDefaultRules(): array
    {
        return array_merge(parent::getDefaultRules(), [
            'user_participation_limit' => 1, // 秒杀通常限制每人只能参与一次
            'product_purchase_limit' => 1, // 每人限购1件
        ]);
    }
    
    protected function validateSpecificData(ActivityData $data): void
    {
        // 秒杀活动特定验证
        if (!$data->rules) {
            throw new \InvalidArgumentException('秒杀活动必须设置活动规则');
        }
        
        // 验证秒杀时间不能太长（比如不超过24小时）
        $duration = $data->endTime->getTimestamp() - $data->startTime->getTimestamp();
        if ($duration > 86400) { // 24小时
            throw new \InvalidArgumentException('秒杀活动时长不能超过24小时');
        }
    }
    
    protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void
    {
        // 检查库存
        $productId = $participationData['product_id'] ?? null;
        if (!$productId) {
            throw new \InvalidArgumentException('必须指定参与的商品');
        }
        
        $activityProduct = $activity->products()->where('product_id', $productId)->first();
        if (!$activityProduct) {
            throw new \RuntimeException('商品未参与此秒杀活动');
        }
        
        if ($activityProduct->stock <= 0) {
            throw new \RuntimeException('商品库存不足');
        }
        
        // 检查用户是否已经参与过
        $existingParticipation = $activity->participations()
            ->where('user_id', $user->getID())
            ->where('user_type', get_class($user))
            ->exists();
            
        if ($existingParticipation) {
            throw new \RuntimeException('您已经参与过此秒杀活动');
        }
        
        // 检查最大参与人数
        $rules = $activity->rules;
        if ($rules && isset($rules['max_participants'])) {
            $currentParticipants = $activity->participations()->count();
            if ($currentParticipants >= $rules['max_participants']) {
                throw new \RuntimeException('活动参与人数已满');
            }
        }
    }
    
    protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float
    {
        // 秒杀价格计算逻辑
        $flashPrice = $activityProduct->activity_price ?? $activityProduct->original_price;
        
        // 秒杀通常是固定价格
        return $flashPrice * $quantity;
    }
    
    protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void
    {
        // 扣减库存
        $productId = $participationData['product_id'];
        $quantity = $participationData['quantity'] ?? 1;
        
        $activityProduct = $activity->products()->where('product_id', $productId)->first();
        $activityProduct->decrement('stock', $quantity);
        
        // 更新活动统计
        $activity->increment('total_participants');
        
        // 记录参与详情
        $activityOrder->update([
            'product_id' => $productId,
            'quantity' => $quantity,
            'original_price' => $activityProduct->original_price,
            'activity_price' => $activityProduct->activity_price,
            'total_amount' => $activityProduct->activity_price * $quantity,
        ]);
    }
    
    protected function canStartSpecific(Activity $activity): bool
    {
        // 秒杀活动需要有商品且有库存
        return $activity->products()->where('stock', '>', 0)->exists();
    }
    
    protected function canEndSpecific(Activity $activity): bool
    {
        // 库存售罄时可以提前结束
        return $activity->products()->where('stock', '>', 0)->doesntExist();
    }
    
    protected function executeStartLogic(Activity $activity): void
    {
        // 秒杀开始时的特定逻辑
        // 比如：预热缓存、发送通知等
    }
    
    protected function executeEndLogic(Activity $activity): void
    {
        // 秒杀结束时的特定逻辑
        // 比如：清理缓存、统计数据等
    }
}
