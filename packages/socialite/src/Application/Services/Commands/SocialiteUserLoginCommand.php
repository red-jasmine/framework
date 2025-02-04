<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Required;

class SocialiteUserLoginCommand extends Data
{

    public string $appId;
    public string $provider;
    public string $clientId;
    #[Required]
    public string $code;

}
