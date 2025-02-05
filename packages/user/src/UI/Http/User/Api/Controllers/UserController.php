<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

class UserController extends Controller
{
    public function info(Request $request) : UserBaseResource
    {
        $user = Auth::user();

        return UserBaseResource::make($user);
    }

    // 修改基本信息

    // 修改安全信息

    // 设置头像

    // 设置昵称

    // 设置密码

    // 修改密码

    // 修改登录信息
    // 修改手机号
    // 修改邮箱

}
