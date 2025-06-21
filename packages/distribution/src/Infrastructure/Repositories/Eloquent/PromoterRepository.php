<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterRepository extends EloquentRepository implements PromoterRepositoryInterface
{
    protected static string $eloquentModelClass = Promoter::class;

    public function findByOwner(UserInterface $owner) : ?Promoter
    {
        return static::$eloquentModelClass::onlyOwner($owner)->first();
    }


}