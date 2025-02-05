<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class SocialiteUserClearCommand extends Data
{
    public string         $appId;
    public ?UserInterface $owner;
    public string         $provider;


}
