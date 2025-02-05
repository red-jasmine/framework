<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommand;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\UserCommandService;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{

    public function __construct(
        protected UserCommandService $commandService

    ) {
    }

    public function info(Request $request) : UserBaseResource
    {
        $user = Auth::user();

        return UserBaseResource::make($user);
    }


    public function updateBaseInfo(Request $request) : JsonResponse|JsonResource
    {

        $request->offsetSet('id', Auth::id());

        $this->commandService->updateBaseInfo(UserUpdateBaseInfoCommand::from($request));

        return static::success();
    }


    public function unbindSocialite(Request $request) : JsonResponse|JsonResource
    {
        $request->offsetSet('id', Auth::id());

        $this->commandService->unbindSocialite(UserUnbindSocialiteCommand::from($request));

        return static::success();
    }



    // 修改安全信息  TODO

    // 设置密码

    // 修改密码

    // 修改登录信息
    // 修改手机号
    // 修改邮箱

}
