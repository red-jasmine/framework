<?php


use RedJasmine\Article\UI\Http\User\CommunityUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    CommunityUserRoute::api();

});