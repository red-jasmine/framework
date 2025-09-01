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
 * 满减活动处理器
 */
class FullReductionActivityHandler extends AbstractActivityTypeHandler
{
    public static string $hookNamePrefix = 'promotion.full_reduction';
    
    public function getActivityType(): string
    {
        return ActivityTypeEnum::FULL_REDUCTION->value;
    }
    
    public function getExtensionFields(): array
    {
        return [
            'threshold_rules' => 'json', // 满减阶梯规则 [{'threshold': 100, 'reduction': 10}, ...]
            'max_reduction_amount' => 'decimal:2', // 最大减免金额
            'applicable_categories' => 'json', // 适用分类
            'stackable' => 'boolean', // 是否可叠加使用
        ];
    }
    
    public function getDefaultRules(): array
    {
        return array_merge(parent::getDefaultRules(), [
            'allow_overlay' => true, // 满减活动通常可以叠加
            'stackable' => false, // 默认不可叠加使用多个满减
            'threshold_rules' => [
                ['threshold' => 100, 'reduction' => 10],
                ['threshold' => 200, 'reduction' => 25],
                ['threshold' => 500, 'reduction' => 70],
            ],
        ]);
    }
    
    protected function validateSpecificData(ActivityData $data): void
    {
        $rules = $data->rules;
        
        if (!$rules) {
            throw new \InvalidArgumentException('满减活动必须设置活动规则');
        }
        
        $thresholdRules = $rules['threshold_rules'] ?? [];
        if (empty($thresholdRules)) {
            throw new \InvalidArgumentException('满减活动必须设置满减阶梯规则');
        }
        
        // 验证阶梯规则格式
        foreach ($thresholdRules as $rule) {
            if (!isset($rule['threshold']) || !isset($rule['reduction'])) {
                throw new \InvalidArgumentException('满减阶梯规则格式错误，必须包含threshold和reduction字段');
            }
            
            if ($rule['threshold'] <= 0 || $rule['reduction'] <= 0) {
                throw new \InvalidArgumentException('满减阶梯规则的门槛和减免金额必须大于0');
            }
            
            if ($rule['reduction'] >= $rule['threshold']) {
                throw new \InvalidArgumentException('减免金额不能大于等于门槛金额');
            }
        }
        
        // 验证阶梯规则是否按门槛金额排序
        $thresholds = array_column($thresholdRules, 'threshold');
        $sortedThresholds = $thresholds;
        sort($sortedThresholds);
        if ($thresholds !== array_values(array_unique($sortedThresholds))) {
            throw new \InvalidArgumentException('满减阶梯规则必须按门槛金额升序排列且不能重复');
        }
    }
    
    protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void
    {
        $totalAmount = $participationData['total_amount'] ?? 0;
        
        if ($totalAmount <= 0) {
            throw new \InvalidArgumentException('购买金额必须大于0');
        }
        
        // 验证是否达到最低门槛
        $thresholdRules = $activity->rules['threshold_rules'] ?? [];
        if (!empty($thresholdRules)) {
            $minThreshold = min(array_column($thresholdRules, 'threshold'));
            if ($totalAmount < $minThreshold) {
                throw new \RuntimeException("购买金额未达到满减门槛，最低需要 {$minThreshold} 元");
            }
        }
        
        // 验证适用分类
        $productIds = $participationData['product_ids'] ?? [];
        if (!empty($productIds)) {
            $this->validateApplicableCategories($activity, $productIds);
        }
    }
    
    protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float
    {
        $originalPrice = $activityProduct->original_price * $quantity;
        $totalCartAmount = $context['total_cart_amount'] ?? $originalPrice;
        
        // 计算满减优惠
        $reductionAmount = $this->calculateReductionAmount($activity, $totalCartAmount);
        
        // 按比例分摊优惠到当前商品
        $productRatio = $originalPrice / $totalCartAmount;
        $productReduction = $reductionAmount * $productRatio;
        
        return max(0, $originalPrice - $productReduction);
    }
    
    protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void
    {
        $totalAmount = $participationData['total_amount'];
        $reductionAmount = $this->calculateReductionAmount($activity, $totalAmount);
        
        $activityOrder->update([
            'original_amount' => $totalAmount,
            'activity_amount' => $totalAmount - $reductionAmount,
            'reduction_amount' => $reductionAmount,
            'threshold_reached' => $this->getReachedThreshold($activity, $totalAmount),
        ]);
        
        // 更新活动统计
        $activity->increment('total_participants');
        $activity->increment('total_sales', $totalAmount - $reductionAmount);
    }
    
    protected function canStartSpecific(Activity $activity): bool
    {
        // 满减活动只要规则配置正确就可以开始
        $thresholdRules = $activity->rules['threshold_rules'] ?? [];
        return !empty($thresholdRules);
    }
    
    protected function canEndSpecific(Activity $activity): bool
    {
        // 满减活动通常按时间结束
        return false;
    }
    
    protected function executeStartLogic(Activity $activity): void
    {
        // 满减活动开始时的特定逻辑
        // 比如：更新商品展示价格、推送营销消息等
    }
    
    protected function executeEndLogic(Activity $activity): void
    {
        // 满减活动结束时的特定逻辑
        // 比如：恢复商品价格、统计活动效果等
    }
    
    // 满减特有的辅助方法
    
    /**
     * 计算满减金额
     */
    private function calculateReductionAmount(Activity $activity, float $totalAmount): float
    {
        $thresholdRules = $activity->rules['threshold_rules'] ?? [];
        $maxReductionAmount = $activity->rules['max_reduction_amount'] ?? null;
        
        $reductionAmount = 0;
        
        // 找到符合条件的最大阶梯
        foreach (array_reverse($thresholdRules) as $rule) {
            if ($totalAmount >= $rule['threshold']) {
                $reductionAmount = $rule['reduction'];
                break;
            }
        }
        
        // 限制最大减免金额
        if ($maxReductionAmount && $reductionAmount > $maxReductionAmount) {
            $reductionAmount = $maxReductionAmount;
        }
        
        return $reductionAmount;
    }
    
    /**
     * 获取达到的门槛
     */
    private function getReachedThreshold(Activity $activity, float $totalAmount): ?float
    {
        $thresholdRules = $activity->rules['threshold_rules'] ?? [];
        
        foreach (array_reverse($thresholdRules) as $rule) {
            if ($totalAmount >= $rule['threshold']) {
                return $rule['threshold'];
            }
        }
        
        return null;
    }
    
    /**
     * 验证适用分类
     */
    private function validateApplicableCategories(Activity $activity, array $productIds): void
    {
        $applicableCategories = $activity->rules['applicable_categories'] ?? [];
        
        if (!empty($applicableCategories)) {
            // 这里需要根据实际的商品服务来验证商品分类
            // 示例逻辑，实际需要注入商品服务
            foreach ($productIds as $productId) {
                // $product = $this->productService->find($productId);
                // if (!in_array($product->category_id, $applicableCategories)) {
                //     throw new \RuntimeException("商品 {$productId} 不在满减活动适用分类范围内");
                // }
            }
        }
    }
    
    /**
     * 计算下一个满减阶梯
     */
    public function getNextThreshold(Activity $activity, float $currentAmount): ?array
    {
        $thresholdRules = $activity->rules['threshold_rules'] ?? [];
        
        foreach ($thresholdRules as $rule) {
            if ($currentAmount < $rule['threshold']) {
                return [
                    'threshold' => $rule['threshold'],
                    'reduction' => $rule['reduction'],
                    'need_amount' => $rule['threshold'] - $currentAmount,
                ];
            }
        }
        
        return null;
    }
}
