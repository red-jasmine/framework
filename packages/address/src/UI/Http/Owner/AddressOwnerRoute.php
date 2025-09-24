<?php

namespace RedJasmine\Address\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Address\UI\Http\Owner\Api\Controllers\AddressController;

class AddressOwnerRoute
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