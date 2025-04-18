<?php


use RedJasmine\Region\UI\Http\RegionRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    RegionRoute::api();

});