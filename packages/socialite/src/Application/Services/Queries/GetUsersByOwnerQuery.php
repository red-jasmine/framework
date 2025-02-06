<?php

namespace RedJasmine\Socialite\Application\Services\Queries;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

class GetUsersByOwnerQuery extends Query
{
    public string $appId;

    public UserInterface $owner;

    public ?string $provider;

}