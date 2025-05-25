<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\Order;

interface OrderNoGeneratorInterface
{
    public function generator(Order $order) : string;

    public function parse(string $UniqueId) : array;
}