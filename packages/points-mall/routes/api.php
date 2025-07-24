<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\Admin\PointsMallAdminRoute;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsProductController;
use RedJasmine\PointsMall\UI\Http\Controllers\PointsExchangeOrderController;
use RedJasmine\PointsMall\UI\Http\Controllers\PointProductCategoryController;


Route::prefix('api/user')
     ->middleware(['auth:user'])
     ->group(function () {


     });

Route::prefix('api/admin')
     ->middleware(['auth:admin'])
     ->group(function () {

         PointsMallAdminRoute::api();

     });