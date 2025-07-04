<?php

namespace RedJasmine\Ecommerce\Domain\Data;


use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Support\Data\Data;

/**
 * 商品金额信息
 */
class ProductAmount extends Data
{

    public int $quantity;

    public Money $price;

    public Money $totalPrice;

    /**
     * 税率
     * @var float
     */
    public float $taxRate = 0;

    protected ?Money $costPrice = null;

    public function getCostPrice() : ?Money
    {
        return $this->costPrice;
    }

    public function setCostPrice(?Money $costPrice) : ProductAmount
    {
        $this->costPrice = $costPrice;
        return $this;
    }

    public function getTotalCostPrice() : ?Money
    {
        return $this->totalCostPrice;
    }

    public function setTotalCostPrice(?Money $totalCostPrice) : ProductAmount
    {
        $this->totalCostPrice = $totalCostPrice;
        return $this;
    }


    protected ?Money $totalCostPrice = null;


    /**
     * 优惠金额
     * @var Money
     */
    public Money $discountAmount;
    /**
     * 税费
     * @var Money
     */
    public Money $taxAmount;
    /**
     * 服务费
     * @var Money
     */
    public Money $serviceAmount;

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

    /**
     * 获取商品金额
     * @return Money
     */
    public function getProductAmount() : Money
    {
        return $this->totalPrice->subtract($this->discountAmount);
    }


}