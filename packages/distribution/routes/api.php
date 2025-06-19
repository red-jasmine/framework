<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\PromoterUserRoute;

Route::group([
    'prefix' => 'api/distribution',
    'middleware' => ['auth:api']
],function () {

    PromoterUserRoute::api();
});
