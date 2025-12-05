<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;

class StockSetCommand extends StockCommand
{

    public UserInterface $owner;

}