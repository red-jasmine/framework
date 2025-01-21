<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class SocialiteUserLoginCommand extends Data
{

    public string $appId;
    public string $provider;
    public string $clientId;
    public string $code;

}
