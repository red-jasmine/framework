<?php

namespace RedJasmine\User\Domain\Services;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommand;
use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQuery;
use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\User\Domain\Models\User;

class UserSocialiteService
{


    public function __construct(
        protected SocialiteUserApplicationService $socialiteUserService,
    ) {
    }


    public const string  APP_ID = 'UserCenter';


    public function getBinds(User $user)
    {
        $query           = new  GetUsersByOwnerQuery;
        $query->owner    = $user;
        $query->appId    = static::APP_ID;
        $query->provider = null;

        return $this->socialiteUserService->getUsersByOwner($query);

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

        return $this->socialiteUserService->clear($command);
    }
}