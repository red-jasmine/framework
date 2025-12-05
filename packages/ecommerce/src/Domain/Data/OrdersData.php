<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var Money
     */
    public Money $total;

    public int $count;


    public UserInterface $buyer;
    /**
     * @var OrderData[]
     */
    public array $orders = [];


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


    public function __construct()
    {
        $this->total = Money::parse(0);

    }

    public function setOrders(array $orders) : void
    {
        $this->orders = $orders;
    }

    public function statistics() : void
    {

        $this->total();

        $this->count = count($this->orders);

    }

    protected function total() : Money
    {
        $this->total = Money::parse(0);

        foreach ($this->orders as $order) {
            $this->total = $this->total->add($order->getOrderAmountInfo()->payableAmount);
        }
        return $this->total;
    }



}
