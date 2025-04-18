<?php

namespace RedJasmine\Region\UI\Http;

use Illuminate\Support\Facades\Route;
use RedJasmine\Region\UI\Http\Api\Controllers\CountryController;
use RedJasmine\Region\UI\Http\Api\Controllers\RegionController;

class RegionRoute
{


    public static function api() : void
    {
        Route::group(['prefix' => 'region'], function () {
            Route::get('regions/tree', [RegionController::class, 'tree']);
            Route::get('regions/children', [RegionController::class, 'children']);
            Route::get('countries/{id}', [CountryController::class, 'show']);
            Route::get('countries', [CountryController::class, 'index']);
        });
    }
}