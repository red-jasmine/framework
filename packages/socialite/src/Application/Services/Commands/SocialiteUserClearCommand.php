<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class SocialiteUserClearCommand extends Data
{
    public string         $appId;
    public ?UserInterface $owner;
    public string         $provider;


}
