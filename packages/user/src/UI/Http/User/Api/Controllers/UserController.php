<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Application\Services\Commands\UserSetPasswordCommand;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommand;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQuery;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\UI\Http\User\Api\Requests\PasswordRequest;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

class UserController extends Controller
{

    public function __construct(
        protected UserApplicationService $service,
    ) {
    }

    // 查询
    public function info(Request $request) : UserBaseResource
    {
        $user = Auth::user();

        return UserBaseResource::make($user);
    }


    /**
     * 获取绑定的社交账号
     *
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function socialites(Request $request) : JsonResponse|JsonResource
    {
        $request->offsetSet('id', Auth::id());
        $result = $this->service->getSocialites(GetSocialitesQuery::from($request));
        return static::success($result);
    }

    /**
     * 更新基础信息
     *
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function updateBaseInfo(Request $request) : JsonResponse|JsonResource
    {

        $request->offsetSet('id', Auth::id());

        $this->service->updateBaseInfo(UserUpdateBaseInfoCommand::from($request));

        return static::success();
    }


    /**
     * 解绑社交账号
     *
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function unbindSocialite(Request $request) : JsonResponse|JsonResource
    {
        $request->offsetSet('id', Auth::id());

        $this->service->unbindSocialite(UserUnbindSocialiteCommand::from($request));

        return static::success();
    }


    /**
     * 修改密码
     *
     * @param  PasswordRequest  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function password(PasswordRequest $request) : JsonResponse|JsonResource
    {
        $request->offsetSet('id', Auth::id());

        $this->service->setPassword(UserSetPasswordCommand::from($request));

        return static::success();
    }


    // 修改用户名
    // 修改手机号
    // 修改邮箱
}
