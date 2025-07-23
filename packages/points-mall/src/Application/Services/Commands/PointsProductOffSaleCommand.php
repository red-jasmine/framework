<?php

namespace RedJasmine\PointsMall\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;

class PointsProductOffSaleCommand
{
    public function __construct(
        public int $id,
        public UserInterface $operator
    ) {
    }
} 