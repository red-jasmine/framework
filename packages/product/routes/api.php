<?php


use RedJasmine\Product\UI\Http\User\ProductUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api']
], function () {


    ProductUserRoute::api();

});