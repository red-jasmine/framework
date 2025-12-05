<?php

namespace RedJasmine\Socialite\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class SocialiteUserData extends Data
{

    public string $provider;
    public string $clientId;
    public string $identity;
    public string $appId;

    public  ?UserInterface $owner;



}
