<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use Cknow\Money\Money;

interface PromotionServiceInterface
{
    /**
     * 获取商品优惠信息
     *
     * @param CartProduct  $product
     * @param Money $originalPrice
     *
     * @return PriceInfo|null
     */
    public function getProductPromotion(CartProduct $product, Money $originalPrice): ?PriceInfo;

    /**
     * 获取购物车级优惠
     *
     * @param array $cartItems
     * @param float $totalAmount
     * @return array
     */
    public function getCartPromotion(array $cartItems, float $totalAmount): array;

    /**
     * 获取用户优惠券
     *
     * @param string $userId
     * @param float $totalAmount
     * @return array
     */
    public function getUserCoupons(string $userId, float $totalAmount): array;

    /**
     * 计算优惠金额
     *
     * @param array $promotions
     * @param float $totalAmount
     * @return float
     */
    public function calculateDiscountAmount(array $promotions, float $totalAmount): float;
} 