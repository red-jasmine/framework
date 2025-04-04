<?php


use RedJasmine\Community\UI\Http\User\CommunityUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    CommunityUserRoute::api();

});