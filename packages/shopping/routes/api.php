<?php

use RedJasmine\Shopping\UI\Http\Buyer\ShoppingBuyerRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api']
], function () {

    ShoppingBuyerRoute::api();
});