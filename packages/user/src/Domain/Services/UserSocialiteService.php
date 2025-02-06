<?php

namespace RedJasmine\User\Domain\Services;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommand;
use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQuery;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Socialite\Application\Services\SocialiteUserQueryService;
use RedJasmine\User\Domain\Models\User;

class UserSocialiteService
{


    public function __construct(
        protected SocialiteUserCommandService $socialiteUserCommandService,
        protected SocialiteUserQueryService $socialiteUserQueryService,


    ) {
    }


    public const string  APP_ID = 'UserCenter';


    public function getBinds(User $user)
    {
        $query           = new  GetUsersByOwnerQuery;
        $query->owner    = $user;
        $query->appId    = static::APP_ID;
        $query->provider = null;
        return $this->socialiteUserQueryService->getUsersByOwner($query);

    }

    /**
     * @param  User  $user
     * @param  string  $provider
     *
     * @return bool
     */
    public function unbind(User $user, string $provider) : bool
    {
        $command = new SocialiteUserClearCommand();

        $command->owner    = $user;
        $command->provider = $provider;
        $command->appId    = static::APP_ID;

        return $this->socialiteUserCommandService->clear($command);
    }
}