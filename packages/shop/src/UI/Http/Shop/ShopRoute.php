<?php

namespace RedJasmine\Shop\UI\Http\Shop;

use RedJasmine\User\UI\Http\User\UserRoute;

class ShopRoute extends UserRoute
{
    public static string $name      = 'shop';
    public static string $guard     = 'shop';
    public static string $namespace = 'RedJasmine\Shop\UI\Http\Shop';
} 