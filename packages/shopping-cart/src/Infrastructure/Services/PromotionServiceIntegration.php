<?php

namespace RedJasmine\ShoppingCart\Infrastructure\Services;

use RedJasmine\ShoppingCart\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use Cknow\Money\Money;

class PromotionServiceIntegration implements PromotionServiceInterface
{
    public function getProductPromotion(CartProductIdentity $identity, Money $originalPrice): ?PriceInfo
    {
        // TODO: 调用营销服务获取商品优惠
        // 这里应该通过HTTP客户端或RPC调用营销服务
        // 暂时返回模拟数据
        $priceInfo = new PriceInfo();
        $priceInfo->price = $originalPrice->multiply(0.8); // 8折优惠
        $priceInfo->originalPrice = $originalPrice;
        $priceInfo->discountAmount = $originalPrice->multiply(0.2);
        $priceInfo->promotionType = 'product_discount';
        $priceInfo->promotionId = 'product_promo_001';
        
        return $priceInfo;
    }

    public function getCartPromotion(array $cartItems, float $totalAmount): array
    {
        // TODO: 调用营销服务获取购物车级优惠
        // 这里应该通过HTTP客户端或RPC调用营销服务
        // 暂时返回模拟数据
        $promotions = [];
        
        // 满减优惠
        if ($totalAmount >= 200) {
            $promotions[] = [
                'type' => 'cart_discount',
                'name' => '满200减30',
                'discount_amount' => 30,
                'min_amount' => 200,
            ];
        }
        
        return $promotions;
    }

    public function getUserCoupons(string $userId, float $totalAmount): array
    {
        // TODO: 调用营销服务获取用户优惠券
        // 这里应该通过HTTP客户端或RPC调用营销服务
        // 暂时返回模拟数据
        return [
            [
                'id' => 'coupon_001',
                'name' => '新人优惠券',
                'discount_amount' => 10,
                'min_amount' => 50,
                'is_available' => true,
            ],
        ];
    }

    public function calculateDiscountAmount(array $promotions, float $totalAmount): float
    {
        // TODO: 调用营销服务计算优惠金额
        // 这里应该通过HTTP客户端或RPC调用营销服务
        // 暂时返回模拟计算
        $discountAmount = 0;
        
        foreach ($promotions as $promotion) {
            if (isset($promotion['discount_amount'])) {
                $discountAmount += $promotion['discount_amount'];
            }
        }
        
        return min($discountAmount, $totalAmount);
    }
} 