<?php

namespace RedJasmine\User\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\User\UI\Http\User\Api\Controllers\ChangeAccountController;
use RedJasmine\User\UI\Http\User\Api\Controllers\ForgotPasswordController;
use RedJasmine\User\UI\Http\User\Api\Controllers\RegisterController;
use RedJasmine\User\UI\Http\User\Api\Controllers\AccountController;
use RedJasmine\User\UI\Http\User\Api\Controllers\LoginController;

class UserRoute
{

    public static string $name      = 'user';
    public static string $guard     = 'user';
    public static string $namespace = 'RedJasmine\User\UI\Http\User';


    public static function api() : void
    {
        Route::name(static::$name.'.api.')
             ->namespace(static::$namespace.'\Api\Controllers')
             ->group(function () {
                 Route::prefix('auth')
                      ->name('auth.')
                      ->group(function () {

                          // 无需登录
                          Route::post('login/login', 'LoginController@login')->name('login.login');
                          Route::post('login/captcha', 'LoginController@captcha')->name('login.captcha');


                          Route::post('register/captcha', 'RegisterController@captcha')->name('register.captcha');
                          Route::post('register/register', 'RegisterController@register')->name('register.register');


                          Route::post('forgot-password/captcha', 'ForgotPasswordController@captcha')->name('forgot-password.captcha');
                          Route::post('forgot-password/forgot-password',
                              'ForgotPasswordController@resetPassword')->name('forgot-password.forgot-password');


                          // 需要登录
                          Route::group([
                              'middleware' => 'auth:'.static::$guard,
                          ], function () {
                              Route::get('info', 'AccountController@info')->name('user.info');
                          });

                      });
                 Route::prefix('account')
                      ->name('account.')
                      ->middleware([
                          'middleware' => 'auth:'.static::$guard,
                      ])
                      ->group(function () {
                          Route::get('info', 'AccountController@info')->name('account.info');
                          // 查询
                          Route::get('socialites', 'AccountController@socialites')->name('account.socialites');


                          Route::put('base-info', 'AccountController@updateBaseInfo')->name('account.update-base-info');


                          Route::post('unbind-socialite', 'AccountController@unbindSocialite')
                               ->name('account.unbind-socialite');

                          Route::group(['prefix' => 'safety'], function () {

                              // 设置密码
                              Route::put('password', 'AccountController@password')->name('account.password');

                              // 更换账号
                              Route::post('change-account/captcha', 'ChangeAccountController@captcha')
                                   ->name('account.change-account.captcha');
                              Route::post('change-account/verify', 'ChangeAccountController@verify')
                                   ->name('account.change-account.verify');
                              Route::post('change-account/change', 'ChangeAccountController@change')
                                   ->name('account.change-account.change');
                          });


                      });


             });


    }

}
