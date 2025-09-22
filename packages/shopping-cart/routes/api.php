<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\ShoppingCart\UI\Http\Buyer\ShoppingCartRoute;

Route::group([
    'prefix'     => 'api/user',
    'middleware' => ['api']
], function () {
    ShoppingCartRoute::api();
});


