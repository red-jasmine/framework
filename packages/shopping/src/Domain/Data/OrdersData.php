<?php

namespace RedJasmine\Shopping\Domain\Data;

use Cknow\Money\Money;
use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var Money
     */
    public Money $total;
    /**
     * @var OrderData[]
     */
    public array $orders = [];


    public function __construct()
    {
        $this->total = Money::parse(0);

    }

    public function setOrders(array $orders) : void
    {
        $this->orders = $orders;
    }

    public function total() : Money
    {
        $this->total = Money::parse(0);


        foreach ($this->orders as $order) {
            $this->total = $this->total->add($order->getOrderAmount()->payableAmount);
        }


        return $this->total;
    }


}
