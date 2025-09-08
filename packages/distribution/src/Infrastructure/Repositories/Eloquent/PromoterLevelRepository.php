<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterLevelRepository extends Repository implements PromoterLevelRepositoryInterface
{
    protected static string $modelClass = PromoterLevel::class;
}