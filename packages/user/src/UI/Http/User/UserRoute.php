<?php

namespace RedJasmine\User\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\User\UI\Http\User\Api\Controllers\LoginController;

class UserRoute
{


    public static function api() : void
    {

        Route::post('login', [LoginController::class, 'login'])->name('user.user.api.login');

    }
}
