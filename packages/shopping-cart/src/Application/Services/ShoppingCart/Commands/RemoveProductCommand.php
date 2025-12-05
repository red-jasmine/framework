<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class RemoveProductCommand extends Data
{
    /**
     * 市场
     * @var string
     */
    public string $market = 'default';

    /**
     * 买家
     * @var UserInterface|null
     */
    public ?UserInterface $buyer;
}
