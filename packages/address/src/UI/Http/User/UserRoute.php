<?php

namespace RedJasmine\Address\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Address\UI\Http\User\Api\Controllers\AddressController;

class UserRoute
{

    public static function api() : void
    {
        Route::group([
            'prefix' => 'address',
            'middleware' => ['auth:api']
        ], function () {
            Route::resource('address', AddressController::class)->names('user.api.address');

        });

    }

}