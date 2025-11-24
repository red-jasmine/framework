<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;

class StockSetCommand extends StockCommand
{

    public UserInterface $owner;

}