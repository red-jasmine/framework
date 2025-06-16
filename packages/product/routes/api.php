<?php


use RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {


    ProductBuyerRoute::api();

});