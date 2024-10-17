<?php

namespace RedJasmine\Order\Application\Mappers;



use RedJasmine\Order\Domain\Data\OrderAddressData;
use RedJasmine\Order\Domain\Models\OrderAddress;

class OrderAddressMapper
{

    public function fromData(OrderAddressData $orderAddressData, OrderAddress $orderAddress) : OrderAddress
    {
        // TODO 设置
        $orderAddress->fill($orderAddressData->toArray());
        return $orderAddress;
    }

}
