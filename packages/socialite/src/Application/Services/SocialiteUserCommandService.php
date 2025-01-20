<?php

namespace RedJasmine\Socialite\Application\Services;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Support\Application\ApplicationCommandService;

class SocialiteUserCommandService extends ApplicationCommandService
{
    protected static string $modelClass = SocialiteUser::class;


    protected static $macros = [
        'bind'   => '',// 绑定
        'unbind' => '', // 解绑
        'login'  => '',// 返回绑定信息
    ];
}
