<?php

namespace RedJasmine\Distribution\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterController;

class PromoterUserRoute
{

    public static function api() : void
    {
        Route::get('promoters/info', [PromoterController::class, 'info']);
        Route::post('promoters/apply', [PromoterController::class, 'apply']);

    }

}
