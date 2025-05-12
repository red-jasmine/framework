<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountCaptchaCommandHandler;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountChangeCommandHandler;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommand;
use RedJasmine\User\Application\Services\Commands\ChangeAccount\ChangeAccountVerifyCommandHandler;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCaptchaCommandHandler;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCommand;
use RedJasmine\User\Application\Services\Commands\ForgotPasswordCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserLoginCaptchaCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginCaptchaCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserLoginCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserRegisterCaptchaCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommand;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserSetGroupCommand;
use RedJasmine\User\Application\Services\Commands\UserSetGroupCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserSetPasswordCommand;
use RedJasmine\User\Application\Services\Commands\UserSetPasswordCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserSetTagsCommand;
use RedJasmine\User\Application\Services\Commands\UserSetTagsCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommand;
use RedJasmine\User\Application\Services\Commands\UserUnbindSocialiteCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommandHandler;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQuery;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQueryHandler;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;

/**
 * @method getSocialites(GetSocialitesQuery $query)
 * @see UserRegisterCommandHandler::handle()
 * @method UserTokenData register(UserRegisterCommand $command)
 * @see UserRegisterCaptchaCommandHandler::handle()
 * @method bool registerCaptcha(UserRegisterCommand $command)
 * @see UserLoginCommandHandler::handle()
 * @method UserTokenData login(UserLoginCommand $command)
 * @method bool  loginCaptcha(UserLoginCaptchaCommand $command)
 * @method UserTokenData loginOrRegister(UserLoginOrRegisterCommand $command)
 * @method bool updateBaseInfo(UserUpdateBaseInfoCommand $command)
 * @method bool unbindSocialite(UserUnbindSocialiteCommand $command)
 * @method bool setPassword(UserSetPasswordCommand $command)
 * @method bool setGroup(UserSetGroupCommand $command)
 * @method bool setTags(UserSetTagsCommand $command)
 * @method bool forgotPasswordCaptcha(ForgotPasswordCaptchaCommand $command)
 * @method bool forgotPassword(ForgotPasswordCommand $command)
 * @method bool changeAccountCaptcha(ChangeAccountCaptchaCommand $command)
 * @method bool changeAccountVerify(ChangeAccountVerifyCommand $command)
 * @method bool changeAccountChange(ChangeAccountChangeCommand $command)
 *
 */
class UserApplicationService extends ApplicationService
{

    public static string    $hookNamePrefix = 'user.application.user';
    protected static string $modelClass     = User::class;
    protected static        $macros         = [
        'update'                => UserUpdateBaseInfoCommandHandler::class,
        'getSocialites'         => GetSocialitesQueryHandler::class,
        'registerCaptcha'       => UserRegisterCaptchaCommandHandler::class,
        'register'              => UserRegisterCommandHandler::class,
        'loginCaptcha'          => UserLoginCaptchaCommandHandler::class,
        'login'                 => UserLoginCommandHandler::class,
        'loginOrRegister'       => UserLoginOrRegisterCommandHandler::class,
        'updateBaseInfo'        => UserUpdateBaseInfoCommandHandler::class,
        'unbindSocialite'       => UserUnbindSocialiteCommandHandler::class,
        'setPassword'           => UserSetPasswordCommandHandler::class,
        'setGroup'              => UserSetGroupCommandHandler::class,
        'setTags'               => UserSetTagsCommandHandler::class,
        'forgotPasswordCaptcha' => ForgotPasswordCaptchaCommandHandler::class,
        'forgotPassword'        => ForgotPasswordCommandHandler::class,
        'changeAccountCaptcha'  => ChangeAccountCaptchaCommandHandler::class,
        'changeAccountVerify'   => ChangeAccountVerifyCommandHandler::class,
        'changeAccountChange'   => ChangeAccountChangeCommandHandler::class,


    ];

    public function __construct(
        public UserRepositoryInterface $repository,
        public UserReadRepositoryInterface $readRepository,

    ) {
    }


}
