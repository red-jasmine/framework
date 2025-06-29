<?php

namespace RedJasmine\Admin\UI\Http\Admin;

use RedJasmine\User\UI\Http\User\UserRoute;

class AdminRoute extends UserRoute
{
    public static string $name      = 'admin';
    public static string $guard     = 'admin';
    public static string $namespace = 'RedJasmine\Admin\UI\Http\Admin';

}
