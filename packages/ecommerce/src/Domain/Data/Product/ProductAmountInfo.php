<?php

namespace RedJasmine\Ecommerce\Domain\Data\Product;


use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 商品金额信息
 */
class ProductAmountInfo extends Data
{

    public int $quantity;

    public Money $price;


    public Money $totalPrice;
    // 市场价格
    public ?Money $marketPrice;


    /**
     * 税率
     * @var float
     */
    public float $taxRate = 0;
    /**
     * 使用的优惠券
     * @var CouponInfoData[]
     */
    public array $coupons = [];

    /**
     * 可用的优惠券
     * @var CouponInfoData[]
     */
    public array $availableCoupons = [];

    /**
     * 优惠金额
     * @var Money
     */
    public Money $discountAmount;

    /**
     * 商品金额 = 单价 * 数量 - 商品优惠
     * @var Money
     */
    public Money $productAmount;


    /**
     * 税费
     * @var Money
     */
    public Money $taxAmount;
    /**
     * 税率
     * @var string|int|float
     */
    public string|int|float $texRate = 0;
    /**
     * 服务费
     * @var Money
     */
    public Money $serviceAmount;


    protected ?Money $costPrice      = null;
    protected ?Money $totalCostPrice = null;

    public function __construct(public Currency $currency)
    {
        $this->price          = Money::parse(0, $currency);
        $this->costPrice      = Money::parse(0, $currency);
        $this->totalCostPrice = Money::parse(0, $currency);
        $this->totalPrice     = Money::parse(0, $currency);
        $this->discountAmount = Money::parse(0, $currency);
        $this->taxAmount      = Money::parse(0, $currency);
        $this->serviceAmount  = Money::parse(0, $currency);

    }

    public function getCostPrice() : ?Money
    {
        return $this->costPrice;
    }

    public function setCostPrice(?Money $costPrice) : ProductAmountInfo
    {
        $this->costPrice = $costPrice;
        return $this;
    }

    public function getTotalCostPrice() : ?Money
    {
        return $this->totalCostPrice;
    }

    public function setTotalCostPrice(?Money $totalCostPrice) : ProductAmountInfo
    {
        $this->totalCostPrice = $totalCostPrice;
        return $this;
    }

    /**
     * 获取商品金额
     * @return Money
     */
    public function getProductAmount() : Money
    {
        $this->productAmount = $this->totalPrice->subtract($this->discountAmount);

        return $this->productAmount;
    }


}