<?php

namespace RedJasmine\Socialite\Application\Services;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserBindCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommandHandler;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommandHandler;
use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQuery;
use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQueryHandler;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Queries\FindQueryHandler;
use RedJasmine\Support\Application\Queries\PaginateQueryHandler;

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
 *
 * @method getUsersByOwner(GetUsersByOwnerQuery $query)
 */
class SocialiteUserApplicationService extends ApplicationService
{

    public function __construct(
        public SocialiteUserRepositoryInterface $repository,
    ) {
    }

    protected static string $modelClass = SocialiteUser::class;


    protected static $macros = [
        'bind'            => SocialiteUserBindCommandHandler::class,
        'unbind'          => SocialiteUserUnbindCommandHandler::class,
        'login'           => SocialiteUserLoginCommandHandler::class,
        'clear'           => SocialiteUserClearCommandHandler::class,
        'findById'        => FindQueryHandler::class,
        'paginate'        => PaginateQueryHandler::class,
        'getUsersByOwner' => GetUsersByOwnerQueryHandler::class
    ];
}
