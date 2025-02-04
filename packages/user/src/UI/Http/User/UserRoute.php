<?php

namespace RedJasmine\User\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\User\UI\Http\User\Api\Controllers\LoginController;

class UserRoute
{


    public static function api() : void
    {

        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::any('info', [LoginController::class, 'info'])->name('user.user.api.info');
        });
        Route::group([

        ], function () {
            Route::post('login', [LoginController::class, 'login'])->name('user.user.api.login');
        });
    }

}
