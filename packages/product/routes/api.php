<?php


use RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api','auth:api']
], function () {


    ProductBuyerRoute::api();

});