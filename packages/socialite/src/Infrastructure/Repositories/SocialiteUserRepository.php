<?php

namespace RedJasmine\Socialite\Infrastructure\Repositories;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class SocialiteUserRepository extends Repository implements SocialiteUserRepositoryInterface
{

    protected static string $modelClass = SocialiteUser::class;
}
