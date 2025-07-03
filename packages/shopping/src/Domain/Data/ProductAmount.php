<?php

namespace RedJasmine\Shopping\Domain\Data;

use App\DTO\ProductAmountDataDTO;
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

    protected ?Money $costPrice {
        get {
            return $this->costPrice;
        }
        set(?Money $value) {
            $this->costPrice = $value;
        }
    }
    protected ?Money $totalCostPrice {
        get {
            return $this->totalCostPrice;
        }
        set(?Money $value) {
            $this->totalCostPrice = $value;
        }
    }


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

    public function getDTO() : ProductAmountDataDTO
    {
        return new ProductAmountDataDTO();
    }
}