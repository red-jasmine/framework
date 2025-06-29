<?php

use RedJasmine\Shop\UI\Http\Shop\ShopRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api/shop',
    'middleware' => ['api'],

], function () {

    ShopRoute::api();

}); 