<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\Refund;

interface RefundNoGeneratorInterface
{
    public function generator(Refund $refund) : string;

    public function parse(string $UniqueId) : array;
}