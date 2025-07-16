<?php

namespace RedJasmine\Address\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Address\UI\Http\User\Api\Controllers\AddressController;

class AddressUserRoute
{

    public static function api() : void
    {
        Route::group([
            'prefix'     => 'address',
            'middleware' => ['auth']
        ], function () {
            Route::resource('address', AddressController::class)->names('user.api.address');

        });

    }

}