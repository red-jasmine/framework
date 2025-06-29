<?php

use RedJasmine\Admin\UI\Http\Admin\AdminRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/admin',
    'middleware' => ['api'],

], function () {

    AdminRoute::api();

});