<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\DistributionUserRoute;

Route::group([
    'prefix' => 'api/distribution',
    'middleware' => ['auth:api']
],function () {

    DistributionUserRoute::api();
});
