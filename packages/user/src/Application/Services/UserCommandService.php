<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\User\Application\Services\Commands\UserLoginCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommand;
use RedJasmine\User\Application\Services\Commands\UserLoginOrRegisterCommandHandler;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommand;
use RedJasmine\User\Application\Services\Commands\UserRegisterCommandHandler;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;

/**
 * @see UserRegisterCommandHandler::handle()
 * @method User register(UserRegisterCommand $command)
 * @see UserLoginCommandHandler::handle()
 * @method UserTokenData login(UserLoginCommand $command)
 * @method UserTokenData loginOrRegister(UserLoginOrRegisterCommand $command)
 *
 */
class UserCommandService extends ApplicationCommandService
{


    public function __construct(
        public UserRepositoryInterface $repository,

    ) {
    }

    protected static string $modelClass = User::class;

    public static string $hookNamePrefix = 'user.application.command.login';


    protected static $macros = [
        'register'        => UserRegisterCommandHandler::class,
        'login'           => UserLoginCommandHandler::class,
        'loginOrRegister' => UserLoginOrRegisterCommandHandler::class
    ];


}
