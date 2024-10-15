<?php

namespace RedJasmine\Card\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Card\UI\Http\Owner\Api\Controllers\CardController;
use RedJasmine\Card\UI\Http\Owner\Api\Controllers\CardGroupBindProductController;
use RedJasmine\Card\UI\Http\Owner\Api\Controllers\CardGroupController;

class CardOwnerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'card' ], function () {

            Route::apiResource('card-groups', CardGroupController::class)->names('owner.api.card.card-groups');
            Route::apiResource('cards', CardController::class)->names('owner.api.card.cards');
            Route::apiResource('card-group-bind-products', CardGroupBindProductController::class)->names('owner.api.card.card-group-bind-products');
            Route::post('card-group-bind-products/bind', [CardGroupBindProductController::class,'bind'])->name('owner.api.card.card-group-bind-products.bind');

        });

    }

}
