<?php

namespace RedJasmine\User\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\User\UI\Http\User\Api\Controllers\ChangeAccountController;
use RedJasmine\User\UI\Http\User\Api\Controllers\ForgotPasswordController;
use RedJasmine\User\UI\Http\User\Api\Controllers\RegisterController;
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
            Route::post('login/captcha', [LoginController::class, 'captcha'])->name('user.user.api.login.captcha');
            Route::post('login/login', [LoginController::class, 'login'])->name('user.user.api.login.login');


            Route::post('register/captcha', [RegisterController::class, 'captcha'])->name('user.user.api.register.captcha');
            Route::post('register/register', [RegisterController::class, 'register'])->name('user.user.api.register.register');


            Route::post('forgot-password/captcha', [ForgotPasswordController::class, 'captcha'])
                 ->name('user.user.api.forgot-password.captcha');
            Route::post('forgot-password/forgot-password', [ForgotPasswordController::class, 'resetPassword'])
                 ->name('user.user.api.forgot-password.forgot-password');


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

                // 更换账号
                Route::post('change-account/captcha', [ChangeAccountController::class, 'captcha'])
                     ->name('user.api.user.change-account.captcha');
                Route::post('change-account/verify', [ChangeAccountController::class, 'verify'])
                     ->name('user.api.user.change-account.verify');
                Route::post('change-account/change', [ChangeAccountController::class, 'change'])
                     ->name('user.api.user.change-account.change');
            });


        });

    }

}
