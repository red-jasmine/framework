<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\OrderProduct;

interface OrderProductNoGeneratorInterface
{
    public function generator(OrderProduct $order) : string;

    public function parse(string $UniqueId) : array;
}