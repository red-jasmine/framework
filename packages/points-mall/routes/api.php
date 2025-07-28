<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\PointsMall\UI\Http\Admin\PointsMallAdminRoute;
use RedJasmine\PointsMall\UI\Http\User\PointsMallUserRoute;

Route::prefix('api/user')
     ->middleware(['auth:user'])
     ->group(function () {
         PointsMallUserRoute::api();
     });

Route::prefix('api/admin')
     ->middleware(['auth:admin'])
     ->group(function () {
         PointsMallAdminRoute::api();
     });