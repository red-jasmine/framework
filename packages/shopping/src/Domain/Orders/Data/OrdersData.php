<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var MoneyOld
     */
    public MoneyOld $total;
    /**
     * @var Collection<OrderData>
     */
    public Collection $orders;

    public function __construct()
    {
        $this->total = MoneyOld::make(0);

    }

    public function setOrders(Collection $orders) : void
    {
        $this->orders = $orders;

    }

    public function total() : MoneyOld
    {
        $this->total = MoneyOld::make(0);


        foreach ($this->orders as $order) {

            $this->total->add($order->getAdditionalData()['payable_amount'] ?? MoneyOld::make(0));
        }


        return $this->total;
    }


}
