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
 * 折扣活动处理器
 */
class DiscountActivityHandler extends AbstractActivityTypeHandler
{
    public static string $hookNamePrefix = 'promotion.discount';
    
    public function getActivityType(): string
    {
        return ActivityTypeEnum::DISCOUNT->value;
    }
    
    public function getExtensionFields(): array
    {
        return [
            'discount_type' => 'string', // 折扣类型：percentage、fixed_amount
            'discount_value' => 'decimal:2', // 折扣值
            'max_discount_amount' => 'decimal:2', // 最大折扣金额
            'min_purchase_amount' => 'decimal:2', // 最低购买金额
            'applicable_products' => 'json', // 适用商品
        ];
    }
    
    public function getDefaultRules(): array
    {
        return array_merge(parent::getDefaultRules(), [
            'discount_type' => 'percentage',
            'discount_value' => 10, // 默认9折
            'allow_overlay' => true, // 折扣活动通常可以叠加
        ]);
    }
    
    protected function validateSpecificData(ActivityData $data): void
    {
        $rules = $data->rules;
        
        if (!$rules) {
            throw new \InvalidArgumentException('折扣活动必须设置活动规则');
        }
        
        $discountType = $rules['discount_type'] ?? 'percentage';
        $discountValue = $rules['discount_value'] ?? 0;
        
        if ($discountType === 'percentage') {
            if ($discountValue <= 0 || $discountValue >= 100) {
                throw new \InvalidArgumentException('百分比折扣值必须在0-100之间');
            }
        } elseif ($discountType === 'fixed_amount') {
            if ($discountValue <= 0) {
                throw new \InvalidArgumentException('固定金额折扣值必须大于0');
            }
        } else {
            throw new \InvalidArgumentException('不支持的折扣类型');
        }
    }
    
    protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void
    {
        // 验证最低购买金额
        $totalAmount = $participationData['total_amount'] ?? 0;
        $minPurchaseAmount = $activity->rules['min_purchase_amount'] ?? 0;
        
        if ($totalAmount < $minPurchaseAmount) {
            throw new \RuntimeException("购买金额不足，最低需要 {$minPurchaseAmount} 元");
        }
        
        // 验证适用商品
        $productIds = $participationData['product_ids'] ?? [];
        if (!empty($productIds)) {
            $this->validateApplicableProducts($activity, $productIds);
        }
    }
    
    protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float
    {
        $originalPrice = $activityProduct->original_price * $quantity;
        $discountType = $activity->rules['discount_type'] ?? 'percentage';
        $discountValue = $activity->rules['discount_value'] ?? 0;
        $maxDiscountAmount = $activity->rules['max_discount_amount'] ?? null;
        
        $discountAmount = 0;
        
        if ($discountType === 'percentage') {
            $discountAmount = $originalPrice * ($discountValue / 100);
        } elseif ($discountType === 'fixed_amount') {
            $discountAmount = $discountValue * $quantity;
        }
        
        // 限制最大折扣金额
        if ($maxDiscountAmount && $discountAmount > $maxDiscountAmount) {
            $discountAmount = $maxDiscountAmount;
        }
        
        return max(0, $originalPrice - $discountAmount);
    }
    
    protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void
    {
        $productIds = $participationData['product_ids'] ?? [];
        $quantities = $participationData['quantities'] ?? [];
        $totalOriginalAmount = 0;
        $totalActivityAmount = 0;
        
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index] ?? 1;
            $activityProduct = $activity->products()->where('product_id', $productId)->first();
            
            if ($activityProduct) {
                $priceInfo = $this->calculatePrice($activity, $activityProduct, $quantity, $participationData);
                $totalOriginalAmount += $priceInfo['original_price'];
                $totalActivityAmount += $priceInfo['activity_price'];
            }
        }
        
        $activityOrder->update([
            'product_ids' => $productIds,
            'quantities' => $quantities,
            'original_amount' => $totalOriginalAmount,
            'activity_amount' => $totalActivityAmount,
            'discount_amount' => $totalOriginalAmount - $totalActivityAmount,
        ]);
        
        // 更新活动统计
        $activity->increment('total_participants');
        $activity->increment('total_sales', $totalActivityAmount);
    }
    
    protected function canStartSpecific(Activity $activity): bool
    {
        // 折扣活动只要有商品就可以开始
        return $activity->products()->exists();
    }
    
    protected function canEndSpecific(Activity $activity): bool
    {
        // 折扣活动通常按时间结束
        return false;
    }
    
    protected function executeStartLogic(Activity $activity): void
    {
        // 折扣活动开始时的特定逻辑
        // 比如：更新商品价格缓存、发送营销通知等
    }
    
    protected function executeEndLogic(Activity $activity): void
    {
        // 折扣活动结束时的特定逻辑
        // 比如：恢复商品原价、清理缓存等
    }
    
    private function validateApplicableProducts(Activity $activity, array $productIds): void
    {
        $applicableProducts = $activity->rules['applicable_products'] ?? [];
        
        if (!empty($applicableProducts)) {
            foreach ($productIds as $productId) {
                if (!in_array($productId, $applicableProducts)) {
                    throw new \RuntimeException("商品 {$productId} 不在此折扣活动范围内");
                }
            }
        }
    }
}
