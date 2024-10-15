<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var Amount
     */
    public Amount $total;
    /**
     * @var Collection<OrderData>
     */
    public Collection $orders;

    public function __construct()
    {
        $this->subtotal = Amount::make(0);

    }

    public function setOrders(Collection $orders) : void
    {
        $this->orders = $orders;
        $this->total();
    }

    public function total() : Amount
    {
        $this->total = Amount::make(0);
        foreach ($this->orders as $order) {
            $this->total->add($order->getAdditionalData()['payable_amount'] ?? 0);
        }
        return $this->total;
    }


}
