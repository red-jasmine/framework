<?php

namespace RedJasmine\Shopping\Domain\Data;

use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Support\Data\Data;

class OrderAmountData extends Data
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
     * @var ProductInfo[]
     */
    public array $products = [];

    public function __construct(public Currency $currency)
    {

        $this->initialize();
    }

    protected function initialize() : void
    {
        $this->productAmount  = Money::parse(0, $this->currency);
        $this->discountAmount = Money::parse(0, $this->currency);
        $this->taxAmount      = Money::parse(0, $this->currency);
        $this->serviceAmount  = Money::parse(0, $this->currency);
        $this->freightAmount  = Money::parse(0, $this->currency);
        $this->payableAmount  = Money::parse(0, $this->currency);
    }


    public function calculate() : void
    {
        $this->initialize();

        foreach ($this->products as $product) {
            $productAmount       = $product->productAmount;
            $this->serviceAmount = $this->serviceAmount->add($productAmount->serviceAmount);
            $this->taxAmount     = $this->taxAmount->add($productAmount->taxAmount);
            $this->productAmount = $this->productAmount->add($productAmount->getProductAmount());

        }

        $this->payableAmount = $this->productAmount
            ->add($this->freightAmount)
            ->add($this->serviceAmount)
            ->add($this->taxAmount)
            ->subtract($this->discountAmount);


    }


}