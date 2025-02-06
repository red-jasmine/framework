<?php

namespace RedJasmine\User\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\User\UI\Http\User\Api\Controllers\UserController;
use RedJasmine\User\UI\Http\User\Api\Controllers\LoginController;

class UserRoute
{


    public static function api(string $guard = 'api') : void
    {
        Route::group([
            'prefix' => 'auth'
        ], function () use ($guard) {

            // 无需登录
            Route::post('login', [LoginController::class, 'login'])->name('user.user.api.login');

            // 需要登录
            Route::group([
                'middleware' => 'auth:'.$guard
            ], function () {
                Route::get('info', [UserController::class, 'info'])->name('user.user.api.info');
            });

        });

        Route::group([
            'prefix'     => 'user',
            'middleware' => 'auth:'.$guard
        ], function () {
            Route::get('info', [UserController::class, 'info'])->name('user.api.user.info');
            // 查询
            Route::get('socialites', [UserController::class, 'socialites'])->name('user.api.user.socialites');


            Route::put('base-info', [UserController::class, 'updateBaseInfo'])->name('user.api.user.update-base-info');


            Route::post('unbind-socialite', [UserController::class, 'unbindSocialite'])
                 ->name('user.api.user.unbind-socialite');

            Route::group(['prefix' => 'safety'], function () {

                // 设置密码
                Route::put('password', [UserController::class, 'password'])->name('user.api.user.password');
            });


        });

    }

}
