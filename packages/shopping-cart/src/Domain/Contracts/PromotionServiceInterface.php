<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactors;

interface PromotionServiceInterface
{
    /**
     * 获取商品优惠信息
     *
     * @param  ProductPurchaseFactors  $productPurchaseFactors
     * @param  Money  $price  优惠金额
     *
     * @return Money|null
     */
    public function getProductPromotion(ProductPurchaseFactors $productPurchaseFactors, Money $price) : ?Money;

    /**
     * 获取购物车级优惠
     *
     * @param  array  $cartItems
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getCartPromotion(array $cartItems, float $totalAmount) : array;

    /**
     * 获取用户优惠券
     *
     * @param  string  $userId
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getUserCoupons(string $userId, float $totalAmount) : array;

    /**
     * 计算优惠金额
     *
     * @param  array  $promotions
     * @param  float  $totalAmount
     *
     * @return float
     */
    public function calculateDiscountAmount(array $promotions, float $totalAmount) : float;
} 