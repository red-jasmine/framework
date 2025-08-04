<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Data\Data;

class AbstractOrderCommand extends Data
{
    protected string       $primaryKey = 'orderNo';
    public string          $orderNo;


}