<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterLevelRepository extends EloquentRepository implements PromoterLevelRepositoryInterface
{
    protected static string $eloquentModelClass = PromoterLevel::class;
}