<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterRepository extends Repository implements PromoterRepositoryInterface
{
    protected static string $modelClass = Promoter::class;

    public function findByOwner(UserInterface $owner) : ?Promoter
    {
        return static::$modelClass::onlyOwner($owner)->first();
    }


}