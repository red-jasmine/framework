<?php

namespace RedJasmine\Product\Tests\UI\Http\Buyer;

use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute;

class BaseTestCase extends TestCase
{

    protected function defineRoutes($router)
    {
        $router->group([ 'prefix' => 'api/buyer' ], function () {
            ProductBuyerRoute::api();
        });
    }

}
