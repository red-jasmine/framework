<?php

namespace RedJasmine\Promotion\Domain\Services;

use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 活动规则引擎
 * 
 * 负责动态规则配置和验证
 */
class ActivityRulesEngine extends Service
{
    public static string $hookNamePrefix = 'promotion.rules_engine';
    
    /**
     * 验证用户是否符合活动参与条件
     * 
     * @param Activity $activity
     * @param UserInterface $user
     * @param array $context
     * @return array ['valid' => bool, 'reasons' => array]
     */
    public function validateUserEligibility(Activity $activity, UserInterface $user, array $context = []): array
    {
        $reasons = [];
        
        // 基础活动状态检查
        if (!$activity->canParticipate()) {
            $reasons[] = '活动当前不可参与';
        }
        
        // 用户要求检查
        $userRequirements = $activity->user_requirements;
        if ($userRequirements) {
            $userReasons = $this->validateUserRequirements($userRequirements, $user);
            $reasons = array_merge($reasons, $userReasons);
        }
        
        // 活动规则检查
        $rules = $activity->rules;
        if ($rules) {
            $ruleReasons = $this->validateActivityRules($activity, $rules, $user, $context);
            $reasons = array_merge($reasons, $ruleReasons);
        }
        
        // 活动类型特定检查
        try {
            $handler = ActivityTypeHandlerFactory::make($activity->type);
            $handler->validateParticipation($activity, $user, $context);
        } catch (\Exception $e) {
            $reasons[] = $e->getMessage();
        }
        
        return [
            'valid' => empty($reasons),
            'reasons' => $reasons,
        ];
    }
    
    /**
     * 验证商品是否符合活动要求
     * 
     * @param Activity $activity
     * @param int $productId
     * @param array $productData
     * @return array
     */
    public function validateProductEligibility(Activity $activity, int $productId, array $productData = []): array
    {
        $reasons = [];
        
        // 商品要求检查
        $productRequirements = $activity->product_requirements;
        if ($productRequirements) {
            $productReasons = $this->validateProductRequirements($productRequirements, $productId, $productData);
            $reasons = array_merge($reasons, $productReasons);
        }
        
        // 检查商品是否在活动中
        $activityProduct = $activity->products()->where('product_id', $productId)->first();
        if (!$activityProduct) {
            $reasons[] = '商品未参与此活动';
        } elseif ($activityProduct->stock <= 0) {
            $reasons[] = '商品库存不足';
        }
        
        return [
            'valid' => empty($reasons),
            'reasons' => $reasons,
        ];
    }
    
    /**
     * 动态计算活动优惠
     * 
     * @param Activity $activity
     * @param array $items [['product_id' => int, 'quantity' => int, 'price' => float], ...]
     * @param array $context
     * @return array
     */
    public function calculateDynamicDiscount(Activity $activity, array $items, array $context = []): array
    {
        $handler = ActivityTypeHandlerFactory::make($activity->type);
        
        $totalOriginalAmount = 0;
        $totalActivityAmount = 0;
        $itemResults = [];
        
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'] ?? 1;
            
            $activityProduct = $activity->products()->where('product_id', $productId)->first();
            if (!$activityProduct) {
                continue;
            }
            
            $priceInfo = $handler->calculatePrice($activityProduct, $activityProduct, $quantity, array_merge($context, [
                'total_cart_amount' => array_sum(array_column($items, 'price')),
                'all_items' => $items,
            ]));
            
            $itemResults[] = array_merge($priceInfo, [
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            
            $totalOriginalAmount += $priceInfo['original_price'];
            $totalActivityAmount += $priceInfo['activity_price'];
        }
        
        return [
            'items' => $itemResults,
            'total_original_amount' => $totalOriginalAmount,
            'total_activity_amount' => $totalActivityAmount,
            'total_discount_amount' => $totalOriginalAmount - $totalActivityAmount,
            'discount_rate' => $totalOriginalAmount > 0 ? (($totalOriginalAmount - $totalActivityAmount) / $totalOriginalAmount) * 100 : 0,
        ];
    }
    
    /**
     * 获取活动的动态配置
     * 
     * @param Activity $activity
     * @return array
     */
    public function getActivityDynamicConfig(Activity $activity): array
    {
        $handler = ActivityTypeHandlerFactory::make($activity->type);
        
        return [
            'type' => $activity->type->value,
            'type_label' => $activity->type->label(),
            'extension_fields' => $handler->getExtensionFields(),
            'default_rules' => $handler->getDefaultRules(),
            'current_rules' => $activity->rules ?? [],
            'can_start' => $handler->canStart($activity),
            'can_end' => $handler->canEnd($activity),
        ];
    }
    
    /**
     * 验证用户要求
     */
    protected function validateUserRequirements($userRequirements, UserInterface $user): array
    {
        $reasons = [];
        
        // 新用户限制
        if ($userRequirements->newUserOnly && $this->isOldUser($user)) {
            $reasons[] = '此活动仅限新用户参与';
        }
        
        // 会员限制
        if ($userRequirements->memberOnly && !$this->isMember($user)) {
            $reasons[] = '此活动仅限会员参与';
        }
        
        // 用户等级限制
        if ($userRequirements->userLevels) {
            $userLevel = $this->getUserLevel($user);
            if (!in_array($userLevel, $userRequirements->userLevels)) {
                $reasons[] = '您的用户等级不符合活动要求';
            }
        }
        
        // 地区限制
        if ($userRequirements->regions) {
            $userRegion = $this->getUserRegion($user);
            if (!in_array($userRegion, $userRequirements->regions)) {
                $reasons[] = '您所在地区不在活动范围内';
            }
        }
        
        // 排除用户
        if ($userRequirements->excludeUsers && in_array($user->getID(), $userRequirements->excludeUsers)) {
            $reasons[] = '您不符合活动参与条件';
        }
        
        return $reasons;
    }
    
    /**
     * 验证活动规则
     */
    protected function validateActivityRules(Activity $activity, array $rules, UserInterface $user, array $context): array
    {
        $reasons = [];
        
        // 用户参与次数限制
        if (isset($rules['user_participation_limit'])) {
            $participationCount = $activity->participations()
                ->where('user_id', $user->getID())
                ->where('user_type', get_class($user))
                ->count();
                
            if ($participationCount >= $rules['user_participation_limit']) {
                $reasons[] = '您已达到活动参与次数上限';
            }
        }
        
        // 商品购买限制
        if (isset($rules['product_purchase_limit']) && isset($context['product_id'])) {
            $productPurchaseCount = $activity->participations()
                ->where('user_id', $user->getID())
                ->where('user_type', get_class($user))
                ->where('product_id', $context['product_id'])
                ->sum('quantity');
                
            $requestQuantity = $context['quantity'] ?? 1;
            if (($productPurchaseCount + $requestQuantity) > $rules['product_purchase_limit']) {
                $reasons[] = '您已达到该商品的购买限制';
            }
        }
        
        return $reasons;
    }
    
    /**
     * 验证商品要求
     */
    protected function validateProductRequirements($productRequirements, int $productId, array $productData): array
    {
        $reasons = [];
        
        // 分类限制
        if ($productRequirements->categories && isset($productData['category_id'])) {
            if (!in_array($productData['category_id'], $productRequirements->categories)) {
                $reasons[] = '商品分类不在活动范围内';
            }
        }
        
        // 品牌限制
        if ($productRequirements->brands && isset($productData['brand_id'])) {
            if (!in_array($productData['brand_id'], $productRequirements->brands)) {
                $reasons[] = '商品品牌不在活动范围内';
            }
        }
        
        // 价格限制
        if ($productRequirements->minPrice && isset($productData['price'])) {
            if ($productData['price'] < $productRequirements->minPrice) {
                $reasons[] = '商品价格低于活动要求';
            }
        }
        
        if ($productRequirements->maxPrice && isset($productData['price'])) {
            if ($productData['price'] > $productRequirements->maxPrice) {
                $reasons[] = '商品价格高于活动限制';
            }
        }
        
        // 排除商品
        if ($productRequirements->excludeProducts && in_array($productId, $productRequirements->excludeProducts)) {
            $reasons[] = '该商品不在活动范围内';
        }
        
        return $reasons;
    }
    
    // 辅助方法 - 需要根据实际业务实现
    
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
    
    protected function getUserRegion(UserInterface $user): ?string
    {
        // 实现获取用户地区的逻辑
        return null;
    }
}
