<?php

namespace RedJasmine\ShoppingCart\Domain\Models\ValueObjects;

use Cknow\Money\Money;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class PriceInfo extends ValueObject
{
    /**
     * 单价
     * @var Money
     */
    public Money $price;

    /**
     * 原价
     * @var Money
     */
    public Money $originalPrice;

    /**
     * 优惠金额
     * @var Money
     */
    public Money $discountAmount;

    /**
     * 促销类型
     * @var string|null
     */
    public ?string $promotionType;

    /**
     * 促销ID
     * @var string|null
     */
    public ?string $promotionId;

    /**
     * 计算最终价格
     * @return Money
     */
    public function getFinalPrice(): Money
    {
        return $this->price->subtract($this->discountAmount);
    }

    /**
     * 获取优惠比例
     * @return float
     */
    public function getDiscountRate(): float
    {
        if ($this->originalPrice->getAmount() <= 0) {
            return 0;
        }
        return round(($this->discountAmount->getAmount() / $this->originalPrice->getAmount()) * 100, 2);
    }
} 