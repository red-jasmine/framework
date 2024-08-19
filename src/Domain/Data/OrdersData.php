<?php

namespace RedJasmine\Shopping\Domain\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class OrdersData extends Data
{

    /**
     * 应付总金额
     * @var Amount
     */
    public Amount $subtotal;
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
        $this->subtotal();
    }


    public function subtotal() : Amount
    {
        $this->subtotal = Amount::make(0);
        foreach ($this->orders as $order) {
            $this->subtotal->add($order->getAdditionalData()['payable_amount'] ?? 0);
        }
        return $this->subtotal;
    }


}
