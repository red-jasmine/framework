<?php

namespace RedJasmine\UserCore\Domain\Exceptions;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Support\Exceptions\BaseException;

class UserNotFoundException extends BaseException
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
