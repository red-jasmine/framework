<?php

namespace RedJasmine\Card\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Card\UI\Http\Owner\Api\Controllers\CardController;

class CardOwnerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'card' ], function () {


            Route::apiResource('cards', CardController::class)->names('owner.api.card.cards');

        });

    }

}
