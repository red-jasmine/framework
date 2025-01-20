<?php

namespace RedJasmine\Socialite\Domain\Repositories;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface SocialiteUserReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findUser(SocialiteUserFindUserQuery $query) : ?SocialiteUser;

}
