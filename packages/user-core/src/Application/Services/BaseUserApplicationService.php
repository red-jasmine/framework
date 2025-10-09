<?php

namespace RedJasmine\UserCore\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommand;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommand;
use RedJasmine\UserCore\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCommand;
use RedJasmine\UserCore\Application\Services\Commands\ForgotPassword\ForgotPasswordCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCaptchaCommand;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCommand;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginOrRegisterCommand;
use RedJasmine\UserCore\Application\Services\Commands\Login\UserLoginOrRegisterCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCaptchaCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCommand;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetAccount\UserSetAccountCommand;
use RedJasmine\UserCore\Application\Services\Commands\SetAccount\UserSetAccountCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetBaseInfo\UserSetBaseInfoCommand;
use RedJasmine\UserCore\Application\Services\Commands\SetBaseInfo\UserSetBaseInfoCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetPassword\UserSetPasswordCommandHandler;
use RedJasmine\UserCore\Application\Services\Commands\SetPassword\UserSetStatusCommand;
use RedJasmine\UserCore\Application\Services\Commands\SetStatus\UserSetPasswordCommand;
use RedJasmine\UserCore\Application\Services\Commands\SetStatus\UserSetStatusCommandHandler;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserTokenData;

/**
 * @property BaseUserRepositoryInterface $repository
 * @see UserRegisterCommandHandler::handle()
 * @method UserTokenData register(UserRegisterCommand $command)
 * @see UserRegisterCaptchaCommandHandler::handle()
 * @method bool registerCaptcha(UserRegisterCommand $command)
 * @see UserLoginCommandHandler::handle()
 * @method UserTokenData login(UserLoginCommand $command)
 * @method bool  loginCaptcha(UserLoginCaptchaCommand $command)
 * @method UserTokenData loginOrRegister(UserLoginOrRegisterCommand $command)
 * @method bool updateBaseInfo(UserSetBaseInfoCommand $command)
 * @method bool setPassword(UserSetPasswordCommand $command)
 * @see UserSetStatusCommandHandler::handle()
 * @method bool setStatus(UserSetStatusCommand $command)
 * @method bool forgotPasswordCaptcha(ForgotPasswordCaptchaCommand $command)
 * @method bool forgotPassword(ForgotPasswordCommand $command)
 * @method bool changeAccountCaptcha(ChangeAccountCaptchaCommand $command)
 * @method bool changeAccountVerify(ChangeAccountVerifyCommand $command)
 * @method bool changeAccountChange(ChangeAccountChangeCommand $command)
 * @see UserSetAccountCommandHandler::handle()
 * @method bool setAccount(UserSetAccountCommand $command)
 *
 */
abstract class BaseUserApplicationService extends ApplicationService
{


    protected static $macros = [
        'update'                => UserSetBaseInfoCommandHandler::class,
        'registerCaptcha'       => UserRegisterCaptchaCommandHandler::class,
        'register'              => UserRegisterCommandHandler::class,
        'loginCaptcha'          => UserLoginCaptchaCommandHandler::class,
        'login'                 => UserLoginCommandHandler::class,
        'loginOrRegister'       => UserLoginOrRegisterCommandHandler::class,
        'updateBaseInfo'        => UserSetBaseInfoCommandHandler::class,
        'setPassword'           => UserSetPasswordCommandHandler::class,
        'setStatus'             => UserSetStatusCommandHandler::class,
        'forgotPasswordCaptcha' => ForgotPasswordCaptchaCommandHandler::class,
        'forgotPassword'        => ForgotPasswordCommandHandler::class,
        'changeAccountCaptcha'  => ChangeAccountCaptchaCommandHandler::class,
        'changeAccountVerify'   => ChangeAccountVerifyCommandHandler::class,
        'changeAccountChange'   => ChangeAccountChangeCommandHandler::class,
        'setAccount'            => UserSetAccountCommandHandler::class,


    ];

    abstract public function getGuard() : string;


}
