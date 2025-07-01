<?php


use RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api']
], function () {


    ProductBuyerRoute::api();

});