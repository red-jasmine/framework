<?php

namespace RedJasmine\Socialite\Application\Services;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserBindCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommandHandler;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see SocialiteUserBindCommandHandler::handle()
 * @method bool  bind(SocialiteUserBindCommand $command)
 *
 * @see SocialiteUserUnbindCommandHandler::handle()
 * @method bool  unbind(SocialiteUserUnbindCommand $command)
 *
 * @see SocialiteUserLoginCommandHandler::handle()
 * @method SocialiteUser  login(SocialiteUserLoginCommand $command)
 *
 * @see SocialiteUserClearCommandHandler::handle()
 * @method bool  clear(SocialiteUserClearCommand $command)
 */
class SocialiteUserCommandService extends ApplicationCommandService
{

    public function __construct(
        public SocialiteUserRepositoryInterface $repository,
        public SocialiteUserReadRepositoryInterface $readRepository
    ) {
    }

    protected static string $modelClass = SocialiteUser::class;


    protected static $macros = [
        'bind'   => SocialiteUserBindCommandHandler::class,
        'unbind' => SocialiteUserUnbindCommandHandler::class,
        'login'  => SocialiteUserLoginCommandHandler::class,
        'clear'  => SocialiteUserClearCommandHandler::class
    ];
}
