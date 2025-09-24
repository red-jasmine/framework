<?php

namespace RedJasmine\User\UI\Http\User\Api\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Application\Services\Commands\UserSetPasswordCommand;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommand;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQuery;
use RedJasmine\User\UI\Http\User\Api\Requests\PasswordRequest;
use RedJasmine\User\UI\Http\User\Api\Resources\UserBaseResource;

/**
 * @property BaseUserApplicationService $service
 */
trait AccountActions
{


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
     * @return JsonResponse
     */
    public function socialites(Request $request) : JsonResponse
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
     * @return JsonResponse
     */
    public function updateBaseInfo(Request $request) : JsonResponse
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
     * @return JsonResponse
     */
    public function unbindSocialite(Request $request) : JsonResponse
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
     * @return JsonResponse
     */
    public function password(PasswordRequest $request) : JsonResponse
    {
        $request->offsetSet('id', Auth::id());

        $this->service->setPassword(UserSetPasswordCommand::from($request));

        return static::success();
    }
}