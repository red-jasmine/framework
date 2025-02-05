<?php

namespace RedJasmine\User\Domain\Services;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserClearCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\User\Domain\Models\User;

class UserSocialiteService
{


    public function __construct(
        protected SocialiteUserCommandService $socialiteUserCommandService

    ) {
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
        $command->appId    = 'UserCenter';

        return $this->socialiteUserCommandService->clear($command);
    }
}