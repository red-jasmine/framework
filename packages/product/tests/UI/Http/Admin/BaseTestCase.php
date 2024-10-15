<?php

namespace RedJasmine\Product\Tests\UI\Http\Admin;

use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Product\UI\Http\Admin\ProductAdminRoute;


class BaseTestCase extends TestCase
{

    protected function defineRoutes($router)
    {
        $router->group([ 'prefix' => 'api/admin' ], function () {
            ProductAdminRoute::api();
        });
    }

}
