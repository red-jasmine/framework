<?php


use RedJasmine\Article\UI\Http\User\ArticleUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    ArticleUserRoute::api();

});