<?php

namespace RedJasmine\User\Domain\Exceptions;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Support\Exceptions\AbstractException;

class UserNotFoundException extends AbstractException
{


    protected ?SocialiteUser $socialiteUser = null;

    public function getSocialiteUser() : ?SocialiteUser
    {
        return $this->socialiteUser;
    }

    public function setSocialiteUser(?SocialiteUser $socialiteUser) : void
    {
        $this->socialiteUser = $socialiteUser;
    }


}
