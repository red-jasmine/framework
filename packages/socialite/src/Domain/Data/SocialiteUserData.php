<?php

namespace RedJasmine\Socialite\Domain\Data;

use RedJasmine\Support\Data\Data;

class SocialiteUserData extends Data
{

    public string $provider;
    public string $clientId;
    public string $identity;
    public string $appId;
    public string $userId;
    public string $userType;


}
