<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class AbstractOrderCommand extends Data
{
    protected string       $primaryKey = 'orderNo';
    public string          $orderNo;


}