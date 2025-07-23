<?php

use RedJasmine\PointsMall\UI\Http\Admin\PointsMallAdminRoute;
use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')
     ->middleware(['auth:admin'])
     ->group(function () {

         PointsMallAdminRoute::api();

     });