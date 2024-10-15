<?php

namespace RedJasmine\Product\Tests\UI\Http\Seller;

use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Product\UI\Http\Seller\ProductSellerRoute;


class BaseTestCase extends TestCase
{

    protected function defineRoutes($router)
    {
        $router->group([ 'prefix' => 'api/seller' ], function () {
            ProductSellerRoute::api();
        });
    }

}
