<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Announcement\UI\Http\Admin\AnnouncementAdminRoute;
use RedJasmine\Announcement\UI\Http\User\AnnouncementUserRoute;

// User API Routes
Route::prefix('api/user')
     ->middleware(['api'])
     ->group(function () {

         AnnouncementUserRoute::api();

     });

// Admin API Routes
Route::prefix('api/admin')
     ->middleware(['api', 'auth:admin'])
     ->group(function () {

         AnnouncementAdminRoute::api();

     });