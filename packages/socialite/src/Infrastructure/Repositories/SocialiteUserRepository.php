<?php

namespace RedJasmine\Socialite\Infrastructure\Repositories;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class SocialiteUserRepository extends EloquentRepository implements SocialiteUserRepositoryInterface
{

    protected static string $eloquentModelClass = SocialiteUser::class;
}
