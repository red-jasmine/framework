<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;
use RedJasmine\Support\Helpers\DomainRoute;

Admin::routes();

Route::group([
                 'domain'     => DomainRoute::domain('admin'),
                 'prefix'     => DomainRoute::adminWebPrefix('user'),
                 'namespace'  => 'RedJasmine\Support\Http\Controllers\Admin',
                 'middleware' => config('admin.route.middleware'),
             ], function (Router $router) {

    $router->get('/', 'HomeController@index');

});
