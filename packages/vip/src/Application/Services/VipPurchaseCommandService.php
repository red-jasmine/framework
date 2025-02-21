<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Shopping\Application\Services\ShoppingOrderCommandService;
use RedJasmine\Support\Application\ApplicationCommandService;

class VipPurchaseCommandService extends ApplicationCommandService
{

    public function __construct(
        public ShoppingOrderCommandService $shoppingOrderCommandService
    ) {
    }


    public function buy()
    {

    }


}