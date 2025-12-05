<?php

namespace RedJasmine\Ecommerce\Domain\Data\Order;

use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

class OrderAmountInfoData extends Data
{


    /**
     * 总商品金额 = SUM( 商品价格 * 数量  - 商品级优惠)
     * @var Money
     */
    public Money $productAmount;

    /**
     * 订单优惠金额
     * @var Money
     */
    public Money $discountAmount;
    /**
     * 总税费
     * @var Money
     */
    public Money $taxAmount;
    /**
     * 总服务费
     * @var Money
     */
    public Money $serviceAmount;
    /**
     * 邮费
     * @var Money
     */
    public Money $freightAmount;
    /**
     * 总应付金额
     * @var Money
     */
    public Money $payableAmount;


    /**
     * 优惠明细
     * @var DiscountBreakdown[]
     */
    public array $discountBreakdowns = [];


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
     * @var ProductAmountInfo[]
     */
    public array $productAmountInfos = [];

    public function __construct(public Currency $currency)
    {

        $this->initialize();
    }

    protected function initialize() : void
    {
        // 累加的需要进行初始化
        $this->productAmount = Money::parse(0, $this->currency);
        $this->taxAmount     = Money::parse(0, $this->currency);
        $this->serviceAmount = Money::parse(0, $this->currency);
        $this->payableAmount = Money::parse(0, $this->currency);
        if (!isset($this->discountAmount)) {
            $this->discountAmount = Money::parse(0, $this->currency);
        }
        if (!isset($this->freightAmount)) {
            $this->freightAmount = Money::parse(0, $this->currency);
        }
        // $this->discountAmount = Money::parse(0, $this->currency);
        // $this->freightAmount  = Money::parse(0, $this->currency);
    }


    public function calculate() : static
    {
        $this->initialize();

        foreach ($this->productAmountInfos as $productAmountInfo) {

            $this->serviceAmount = $this->serviceAmount->add($productAmountInfo->serviceAmount);
            $this->taxAmount     = $this->taxAmount->add($productAmountInfo->taxAmount);
            $this->productAmount = $this->productAmount->add($productAmountInfo->getProductAmount());
        }

        $this->payableAmount = $this->productAmount
            ->add($this->freightAmount)
            ->add($this->serviceAmount)
            ->add($this->taxAmount)
            ->subtract($this->discountAmount);

        return $this;
    }


}