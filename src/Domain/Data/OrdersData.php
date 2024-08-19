<?php

namespace RedJasmine\Shopping\Domain\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class OrdersData extends Data
{

    public function __construct(Collection $orders)
    {
        $this->init();

        $this->setOrders($orders);

    }

    /**
     * 应付总金额
     * @var Amount
     */
    public Amount $totalPayableAmount;


    protected function init() : void
    {
        $this->totalPayableAmount = Amount::make(0);
    }

    /**
     * @var Collection<OrderData>
     */
    public Collection $orders;

    public function setOrders(Collection $orders) : void
    {
        $this->orders = $orders;

        foreach ($this->orders as $order) {


            $this->totalPayableAmount->add($order->getAdditionalData()['payable_amount']);
        }

    }


}
