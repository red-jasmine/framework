<?php

use RedJasmine\Community\UI\Http\Owner\CommunityOwnerRoute;
use RedJasmine\Community\UI\Http\User\CommunityUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    // 用户端路由
    CommunityUserRoute::api();


});
