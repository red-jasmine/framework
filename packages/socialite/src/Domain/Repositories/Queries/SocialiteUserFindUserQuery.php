<?php

namespace RedJasmine\Socialite\Domain\Repositories\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class SocialiteUserFindUserQuery extends Query
{
    public string $provider;
    public string $clientId;
    public string $identity;
    public string $appId;

}
