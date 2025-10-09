<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\User\Application\Services\Commands\UserCancelCommand;
use RedJasmine\User\Application\Services\Commands\UserCancelCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserSetGroupCommand;
use RedJasmine\User\Application\Services\Commands\UserSetGroupCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserSetTagsCommand;
use RedJasmine\User\Application\Services\Commands\UserSetTagsCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommand;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommandHandler;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQuery;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQueryHandler;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginOrRegisterCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetAccount\UserSetAccountCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetBaseInfo\UserSetBaseInfoCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetPassword\UserSetPasswordCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetStatus\UserSetStatusCommandHandler;

/**
 * @method getSocialites(GetSocialitesQuery $query)
 * @method bool unbindSocialite(UserUnbindSocialiteCommand $command)
 * @method bool setGroup(UserSetGroupCommand $command)
 * @see UserSetTagsCommandHandler::handle()
 * @method bool setTags(UserSetTagsCommand $command)
 * @method bool cancel(UserCancelCommand $command)
 */
abstract class BaseUserApplicationService extends \RedJasmine\UserCore\Application\Services\BaseUserApplicationService
{

    protected static $macros = [
        'update'                => UserSetBaseInfoCommandHandler::class,
        'getSocialites'         => GetSocialitesQueryHandler::class,
        'registerCaptcha'       => UserRegisterCaptchaCommandHandler::class,
        'register'              => UserRegisterCommandHandler::class,
        'loginCaptcha'          => UserLoginCaptchaCommandHandler::class,
        'login'                 => UserLoginCommandHandler::class,
        'loginOrRegister'       => UserLoginOrRegisterCommandHandler::class,
        'updateBaseInfo'        => UserSetBaseInfoCommandHandler::class,
        'unbindSocialite'       => UserUnbindSocialiteCommandHandler::class,
        'cancel'                => UserCancelCommandHandler::class,
        'setPassword'           => UserSetPasswordCommandHandler::class,
        'setGroup'              => UserSetGroupCommandHandler::class,
        'setTags'               => UserSetTagsCommandHandler::class,
        'setStatus'             => UserSetStatusCommandHandler::class,
        'forgotPasswordCaptcha' => ForgotPasswordCaptchaCommandHandler::class,
        'forgotPassword'        => ForgotPasswordCommandHandler::class,
        'changeAccountCaptcha'  => ChangeAccountCaptchaCommandHandler::class,
        'changeAccountVerify'   => ChangeAccountVerifyCommandHandler::class,
        'changeAccountChange'   => ChangeAccountChangeCommandHandler::class,
        'setAccount'            => UserSetAccountCommandHandler::class,


    ];
}